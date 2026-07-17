/**
 * game.js — Core cricket batting game loop, physics, scoring, and UI wiring
 */

import * as THREE from "three";
import { buildStadium } from "./stadium.js";
import {
  createBatsman,
  createBowler,
  createBall,
  startSwing,
  updateSwing,
  updateBowler,
  startBowlerRunUp,
} from "./players.js";
import {
  unlockAudio,
  startCrowdAmbience,
  playBatHit,
  playCrowdCheer,
  playCrowdOoh,
  playStumpHit,
} from "./audio.js";

/** Delivery archetypes the computer bowler can bowl */
const DELIVERIES = {
  fast: {
    label: "Fast Ball",
    speed: 32,
    bounceHeight: 1.35,
    length: 0.55, // fraction of pitch — fuller is higher
    lateral: 0,
    runSpeed: 9.5,
  },
  slow: {
    label: "Slow Ball",
    speed: 18,
    bounceHeight: 1.5,
    length: 0.5,
    lateral: 0.15,
    runSpeed: 7.5,
  },
  yorker: {
    label: "Yorker",
    speed: 30,
    bounceHeight: 0.55,
    length: 0.92, // very full, near toes
    lateral: 0,
    runSpeed: 9.2,
  },
  bouncer: {
    label: "Bouncer",
    speed: 31,
    bounceHeight: 2.4,
    length: 0.35, // short of a length
    lateral: -0.05,
    runSpeed: 9.8,
  },
};

const BALLS_PER_OVER = 6;
const MAX_WICKETS = 10;
const BOUNDARY_RADIUS = 68;

export class CricketGame {
  constructor(canvas) {
    this.canvas = canvas;
    this.clock = new THREE.Clock();
    this.state = "menu"; // menu | playing | between | gameover

    // Match stats
    this.stats = this.createEmptyStats();
    this.maxOvers = 5;

    // Ball flight
    this.ballState = {
      active: false,
      phase: "none", // flight | bounce | postHit | dead
      pos: new THREE.Vector3(),
      vel: new THREE.Vector3(),
      bounced: false,
      hit: false,
      delivery: null,
      age: 0,
    };

    this.pendingShot = null; // queued input during delivery
    this.deliveryQueue = null;
    this.betweenTimer = 0;
    this.resultHold = 0;

    this._initThree();
    this._initWorld();
    this._bindUI();
    this._bindInput();
    this._onResize();
    window.addEventListener("resize", () => this._onResize());

    this._animate();
  }

  createEmptyStats() {
    return {
      runs: 0,
      balls: 0,
      wickets: 0,
      dots: 0,
      fours: 0,
      sixes: 0,
    };
  }

  _initThree() {
    try {
      this.renderer = new THREE.WebGLRenderer({
        canvas: this.canvas,
        antialias: true,
        powerPreference: "high-performance",
      });
    } catch (err) {
      console.error(err);
      const msg = document.createElement("div");
      msg.className = "overlay";
      msg.innerHTML =
        '<div class="panel"><p class="brand">Boundary Blitz</p><h1>WebGL Required</h1><p class="subtitle">This game needs a browser with WebGL enabled.</p></div>';
      document.body.appendChild(msg);
      throw err;
    }
    this.renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    this.renderer.shadowMap.enabled = true;
    this.renderer.shadowMap.type = THREE.PCFSoftShadowMap;
    this.renderer.outputColorSpace = THREE.SRGBColorSpace;
    this.renderer.toneMapping = THREE.ACESFilmicToneMapping;
    this.renderer.toneMappingExposure = 1.05;

    this.scene = new THREE.Scene();
    this.scene.fog = new THREE.FogExp2(0x8eb8d8, 0.0045);

    // Camera behind / slightly beside batsman, looking down the pitch
    this.camera = new THREE.PerspectiveCamera(50, 1, 0.1, 400);
    this.camera.position.set(0.8, 2.4, 14.5);
    this.camera.lookAt(0, 1.2, 0);
  }

  _initWorld() {
    this.stadium = buildStadium(this.scene);
    const { batsmanCreaseZ, bowlerCreaseZ } = this.stadium.dims;

    this.batsman = createBatsman(batsmanCreaseZ);
    this.bowler = createBowler(bowlerCreaseZ);
    this.ball = createBall();
    this.scene.add(this.batsman, this.bowler, this.ball);

    this.dims = this.stadium.dims;
    this.batHitBox = new THREE.Box3();
  }

  _bindUI() {
    this.els = {
      startScreen: document.getElementById("start-screen"),
      gameOver: document.getElementById("game-over"),
      hud: document.getElementById("hud"),
      startBtn: document.getElementById("start-btn"),
      playAgainBtn: document.getElementById("play-again-btn"),
      oversSelect: document.getElementById("overs-select"),
      runs: document.getElementById("runs-display"),
      wickets: document.getElementById("wickets-display"),
      balls: document.getElementById("balls-display"),
      overs: document.getElementById("overs-display"),
      sr: document.getElementById("sr-display"),
      dots: document.getElementById("dots-display"),
      deliveryBanner: document.getElementById("delivery-banner"),
      resultBanner: document.getElementById("result-banner"),
      shotHint: document.getElementById("shot-hint"),
      finalSummary: document.getElementById("final-summary"),
      finalStats: document.getElementById("final-stats"),
    };

    this.els.startBtn.addEventListener("click", () => {
      unlockAudio();
      startCrowdAmbience();
      this.maxOvers = parseInt(this.els.oversSelect.value, 10) || 5;
      this.startMatch();
    });

    this.els.playAgainBtn.addEventListener("click", () => {
      this.els.gameOver.classList.add("hidden");
      this.els.startScreen.classList.remove("hidden");
      this.state = "menu";
    });
  }

  _bindInput() {
    const map = {
      ArrowLeft: "left",
      ArrowRight: "right",
      ArrowUp: "straight",
      ArrowDown: "defense",
      " ": "power",
      Space: "power",
    };

    window.addEventListener("keydown", (e) => {
      if (this.state !== "playing") return;
      const shot = map[e.key] || map[e.code];
      if (!shot) return;
      e.preventDefault();
      this._tryShot(shot);
    });
  }

  _tryShot(type) {
    // Allow queuing shortly before ball arrives; swing when near impact zone
    if (!this.ballState.active && this.state === "playing") {
      this.pendingShot = type;
      this.els.shotHint.textContent = `Shot ready: ${this._shotLabel(type)}`;
      return;
    }

    if (this.ballState.active && !this.ballState.hit) {
      const power = type === "power" ? 1.35 : type === "defense" ? 0.55 : 1;
      const started = startSwing(this.batsman, type === "power" ? "power" : type, power);
      if (started) {
        this.pendingShot = type;
        this.els.shotHint.textContent = `Playing ${this._shotLabel(type)}…`;
      }
    }
  }

  _shotLabel(type) {
    return (
      {
        left: "left",
        right: "right",
        straight: "straight drive",
        defense: "defense",
        power: "POWER",
      }[type] || type
    );
  }

  startMatch() {
    this.stats = this.createEmptyStats();
    this.state = "playing";
    this.pendingShot = null;
    this.ballState.active = false;
    this.els.startScreen.classList.add("hidden");
    this.els.gameOver.classList.add("hidden");
    this.els.hud.classList.remove("hidden");
    this._updateScoreboard();
    this._showResult("");
    this.els.shotHint.textContent = "Get ready… bowler coming in";
    this.betweenTimer = 1.2;
    this.state = "between";
  }

  _oversComplete() {
    return this.stats.balls >= this.maxOvers * BALLS_PER_OVER;
  }

  _checkGameOver() {
    if (this.stats.wickets >= MAX_WICKETS || this._oversComplete()) {
      this._endMatch();
      return true;
    }
    return false;
  }

  _endMatch() {
    this.state = "gameover";
    this.ballState.active = false;
    this.ball.visible = false;
    this.els.hud.classList.add("hidden");

    const sr =
      this.stats.balls > 0
        ? ((this.stats.runs / this.stats.balls) * 100).toFixed(1)
        : "0.0";
    const reason =
      this.stats.wickets >= MAX_WICKETS
        ? "All out!"
        : `${this.maxOvers} overs complete.`;

    this.els.finalSummary.textContent = `${reason} You scored ${this.stats.runs} runs.`;
    this.els.finalStats.innerHTML = `
      <div class="stat-card"><span class="label">Runs</span><span class="value">${this.stats.runs}</span></div>
      <div class="stat-card"><span class="label">Wickets</span><span class="value">${this.stats.wickets}</span></div>
      <div class="stat-card"><span class="label">Balls</span><span class="value">${this.stats.balls}</span></div>
      <div class="stat-card"><span class="label">Strike Rate</span><span class="value">${sr}</span></div>
      <div class="stat-card"><span class="label">Fours</span><span class="value">${this.stats.fours}</span></div>
      <div class="stat-card"><span class="label">Sixes</span><span class="value">${this.stats.sixes}</span></div>
    `;
    this.els.gameOver.classList.remove("hidden");
  }

  _pickDelivery() {
    const keys = Object.keys(DELIVERIES);
    // Weight: more fast balls, occasional specials
    const weights = { fast: 0.35, slow: 0.2, yorker: 0.22, bouncer: 0.23 };
    let r = Math.random();
    for (const k of keys) {
      r -= weights[k];
      if (r <= 0) return { key: k, ...DELIVERIES[k] };
    }
    return { key: "fast", ...DELIVERIES.fast };
  }

  _beginDelivery() {
    if (this._checkGameOver()) return;

    this.state = "playing";
    this.deliveryQueue = this._pickDelivery();
    this.pendingShot = null;
    this.ballState.hit = false;
    this.ballState.bounced = false;
    this.ballState.active = false;
    this.ball.visible = false;

    this._showBanner(this.els.deliveryBanner, this.deliveryQueue.label);
    this.els.shotHint.textContent = "Choose your shot with the arrow keys / space";
    startBowlerRunUp(this.bowler);
  }

  /** Called when bowler's arm releases the ball */
  _releaseBall() {
    const d = this.deliveryQueue;
    const { bowlerCreaseZ, batsmanCreaseZ, PITCH_LEN } = this.dims;

    const releasePos = new THREE.Vector3(
      0.35 + d.lateral,
      2.05,
      this.bowler.position.z + 0.4
    );

    // Pitch-of-ball target along the strip
    const pitchZ =
      bowlerCreaseZ + (batsmanCreaseZ - bowlerCreaseZ) * d.length + (Math.random() - 0.5) * 0.4;
    const pitchX = d.lateral + (Math.random() - 0.5) * 0.25;

    const speed = d.speed * (0.95 + Math.random() * 0.1);
    const toPitch = new THREE.Vector3(pitchX, 0.05, pitchZ).sub(releasePos);
    const dist = toPitch.length();
    const timeToPitch = dist / speed;

    // Vertical: start high, arrive at bounce height ~0, with gravity-like arc
    // We use kinematic: y = y0 + vy*t - 0.5*g*t^2, at t=timeToPitch, y≈0.05
    const g = 18;
    const y0 = releasePos.y;
    const vy = (0.05 - y0 + 0.5 * g * timeToPitch * timeToPitch) / timeToPitch;

    this.ballState = {
      active: true,
      phase: "flight",
      pos: releasePos.clone(),
      vel: new THREE.Vector3(
        (pitchX - releasePos.x) / timeToPitch,
        vy,
        (pitchZ - releasePos.z) / timeToPitch
      ),
      bounced: false,
      hit: false,
      delivery: d,
      age: 0,
      g,
      bounceHeight: d.bounceHeight,
      targetPitchZ: pitchZ,
      batsmanCreaseZ,
      PITCH_LEN,
    };

    this.ball.position.copy(releasePos);
    this.ball.visible = true;

    // Auto-trigger queued shot near impact if player chose early
    if (this.pendingShot) {
      const type = this.pendingShot;
      // Delay swing until ball is closer — handled in update
      this._queuedEarlyShot = type;
    }
  }

  _updateBall(dt) {
    const b = this.ballState;
    if (!b.active) return;

    b.age += dt;

    if (b.phase === "flight" || b.phase === "bounce") {
      b.vel.y -= b.g * dt;
      b.pos.addScaledVector(b.vel, dt);

      // Bounce on pitch / ground
      if (b.pos.y <= 0.05 && b.vel.y < 0) {
        b.pos.y = 0.05;
        if (!b.bounced) {
          b.bounced = true;
          b.phase = "bounce";
          // Rebound toward batsman with delivery bounce height
          const remaining = Math.max(0.5, b.batsmanCreaseZ - b.pos.z);
          const tFlight = remaining / Math.max(4, Math.abs(b.vel.z) * 0.85);
          b.vel.y = b.bounceHeight / Math.max(0.15, tFlight) * 0.55;
          b.vel.x *= 0.9;
          b.vel.z *= 0.92;
        } else {
          b.vel.y *= -0.35;
          b.vel.x *= 0.85;
          b.vel.z *= 0.85;
          if (Math.abs(b.vel.y) < 0.8) b.vel.y = 0;
        }
      }

      this.ball.position.copy(b.pos);
      this.ball.rotation.x += dt * 12;
      this.ball.rotation.z += dt * 8;

      // Early-queued shot: start swing when ball approaches the strike zone
      if (this._queuedEarlyShot && b.pos.z > b.batsmanCreaseZ - 2.8) {
        const type = this._queuedEarlyShot;
        this._queuedEarlyShot = null;
        const power = type === "power" ? 1.35 : type === "defense" ? 0.55 : 1;
        startSwing(this.batsman, type === "power" ? "power" : type, power);
      }

      // Collision: bat sweet spot while swinging
      this._checkBatCollision();

      // Wicket: ball hits stumps zone without being hit
      if (
        !b.hit &&
        b.pos.z >= b.batsmanCreaseZ - 0.15 &&
        b.pos.z <= b.batsmanCreaseZ + 0.25 &&
        Math.abs(b.pos.x) < 0.22 &&
        b.pos.y < 0.75
      ) {
        this._resolveWicket("Bowled!");
        return;
      }

      // Missed / past batsman — dead ball (dot or bye-less dot)
      if (!b.hit && b.pos.z > b.batsmanCreaseZ + 2.5) {
        this._resolveDot("Dot ball");
        return;
      }

      // Ball went to ground far behind without contact (wide-ish miss as dot)
      if (!b.hit && b.age > 4.5) {
        this._resolveDot("Dot ball");
        return;
      }
    } else if (b.phase === "postHit") {
      b.vel.y -= b.g * dt;
      b.pos.addScaledVector(b.vel, dt);
      if (b.pos.y < 0.05) {
        b.pos.y = 0.05;
        b.vel.y *= -0.4;
        b.vel.x *= 0.92;
        b.vel.z *= 0.92;
        if (Math.abs(b.vel.y) < 1.2) b.vel.y = 0;
      }
      this.ball.position.copy(b.pos);
      this.ball.rotation.x += dt * 18;

      // Score when ball settles / crosses boundary / times out
      const flatSpeed = Math.hypot(b.vel.x, b.vel.z);
      const radial = Math.hypot(b.pos.x, b.pos.z);

      if (radial >= BOUNDARY_RADIUS) {
        const isSix = b.maxHeight > 4.5 || (!b.groundedBeforeBoundary && b.pos.y > 0.5);
        this._resolveRuns(isSix ? 6 : 4);
        return;
      }

      if (b.pos.y <= 0.06) b.groundedBeforeBoundary = true;
      b.maxHeight = Math.max(b.maxHeight || 0, b.pos.y);

      if (b.age > 0.35 && flatSpeed < 2.5 && b.pos.y <= 0.08) {
        this._resolveLandingRuns(b.pos);
        return;
      }

      if (b.age > 5) {
        this._resolveLandingRuns(b.pos);
      }
    }
  }

  _checkBatCollision() {
    const b = this.ballState;
    if (!b.active || b.hit || b.phase === "postHit") return;

    // Swing pose is advanced in the main loop; only test the sweet-spot window here
    const swing = this.batsman.userData.swing;
    if (!swing.active || !swing.hasHitOpportunity) return;

    const t = swing.t;
    const inSweet = t >= swing.hitWindowStart && t <= swing.hitWindowEnd;
    if (!inSweet) return;

    // Approximate bat world position from right arm / blade
    const bat = this.batsman.userData.bat;
    const batWorld = new THREE.Vector3();
    bat.getWorldPosition(batWorld);
    const bladeTip = new THREE.Vector3(0, -0.7, 0);
    bat.localToWorld(bladeTip);

    const mid = batWorld.clone().lerp(bladeTip, 0.55);
    const dist = mid.distanceTo(b.pos);

    // Generous radius for arcade-friendly timing
    if (dist < 0.65 && b.pos.z > this.dims.batsmanCreaseZ - 2.4) {
      this._onBatHit(swing.type, swing.power, mid);
    }
  }

  _onBatHit(shotType, power, contactPoint) {
    const b = this.ballState;
    b.hit = true;
    b.phase = "postHit";
    b.age = 0;
    b.maxHeight = b.pos.y;
    b.groundedBeforeBoundary = false;
    this.batsman.userData.swing.hasHitOpportunity = false;

    playBatHit(power);

    // Direction based on shot
    let dir = new THREE.Vector3(0, 0, -1); // straight down the ground
    let loft = 0.35;
    let speedMul = 1;

    switch (shotType) {
      case "left":
        dir.set(-0.85, 0, -0.55);
        loft = 0.4;
        break;
      case "right":
        dir.set(0.85, 0, -0.55);
        loft = 0.4;
        break;
      case "straight":
        dir.set((Math.random() - 0.5) * 0.15, 0, -1);
        loft = 0.3;
        break;
      case "defense":
        dir.set((Math.random() - 0.5) * 0.3, 0, -0.4);
        loft = 0.08;
        speedMul = 0.35;
        break;
      case "power":
        dir.set((Math.random() - 0.5) * 0.35, 0, -1);
        loft = 0.75;
        speedMul = 1.45;
        break;
    }

    // Timing quality: better when ball is near optimal contact height / distance
    const heightErr = Math.abs(b.pos.y - 0.75);
    const timing = Math.max(0.45, 1 - heightErr * 0.5);
    // Yorker harder to loft
    if (b.delivery?.key === "yorker" && shotType !== "defense") {
      speedMul *= 0.75;
      loft *= 0.6;
    }
    // Bouncer: pull/hook style if power/left/right, else risk
    if (b.delivery?.key === "bouncer") {
      if (shotType === "defense") {
        speedMul *= 0.5;
      } else if (b.pos.y > 1.4) {
        loft += 0.25;
        speedMul *= 1.1;
      } else {
        // Mis-timed bouncer → chance of edge/out
        if (Math.random() < 0.25) {
          this._resolveWicket("Caught behind!");
          return;
        }
      }
    }

    dir.normalize();
    const hitSpeed = (22 + power * 10) * speedMul * timing;
    b.vel.set(dir.x * hitSpeed, loft * hitSpeed, dir.z * hitSpeed);
    b.pos.copy(contactPoint);

    this.els.shotHint.textContent = "Ball in play…";
  }

  _resolveLandingRuns(pos) {
    const radial = Math.hypot(pos.x, pos.z);
    const downPitch = -pos.z; // positive toward bowler end / outfield long-on

    let runs = 0;
    if (radial < 8) {
      runs = 0;
    } else if (radial < 18) {
      runs = 1;
    } else if (radial < 32) {
      runs = 2;
    } else if (radial < 48) {
      runs = 3;
    } else if (radial < BOUNDARY_RADIUS) {
      // Near boundary but stopped — still 3, or 4 if very close
      runs = radial > 58 ? 4 : 3;
    } else {
      runs = 4;
    }

    // Straight drives that travel deep reward well
    if (downPitch > 40 && runs >= 2 && runs < 4) runs = Math.min(4, runs + 1);

    if (runs === 0) this._resolveDot("Dot ball");
    else this._resolveRuns(runs);
  }

  _resolveRuns(runs) {
    if (this.state !== "playing") return;
    this.stats.runs += runs;
    this.stats.balls += 1;
    if (runs === 4) this.stats.fours += 1;
    if (runs === 6) this.stats.sixes += 1;

    this._updateScoreboard();
    this._showResult(runs === 6 ? "SIX!" : runs === 4 ? "FOUR!" : `${runs} RUN${runs > 1 ? "S" : ""}`, {
      six: runs === 6,
      four: runs === 4,
    });

    if (runs >= 4) playCrowdCheer(runs === 6 ? 1.4 : 1);
    else if (runs >= 2) playCrowdCheer(0.55);

    this._finishBall();
  }

  _resolveDot(label) {
    if (this.state !== "playing") return;
    this.stats.balls += 1;
    this.stats.dots += 1;
    this._updateScoreboard();
    this._showResult(label || "Dot ball");
    playCrowdOoh();
    this._finishBall();
  }

  _resolveWicket(label) {
    if (this.state !== "playing") return;
    this.stats.balls += 1;
    this.stats.wickets += 1;
    this._updateScoreboard();
    this._showResult(label || "WICKET!", { wicket: true });
    playStumpHit();
    playCrowdOoh();

    // Knock bails visually
    const bail = this.stadium.batsmanStumps.children.find((c) => c.name === "bail");
    if (bail) {
      bail.position.y += 0.4;
      bail.rotation.z = 0.8;
      setTimeout(() => {
        bail.position.y = 0.73;
        bail.rotation.z = 0;
      }, 1200);
    }

    this._finishBall();
  }

  _finishBall() {
    this.ballState.active = false;
    this.ballState.phase = "dead";
    this._queuedEarlyShot = null;
    this.pendingShot = null;

    setTimeout(() => {
      this.ball.visible = false;
    }, 400);

    if (this._checkGameOver()) return;

    this.state = "between";
    this.betweenTimer = 2.0;
    this.els.shotHint.textContent = "Next ball coming…";
  }

  _updateScoreboard() {
    const s = this.stats;
    this.els.runs.textContent = String(s.runs);
    this.els.wickets.textContent = String(s.wickets);
    this.els.balls.textContent = String(s.balls);
    this.els.dots.textContent = String(s.dots);

    const overs = Math.floor(s.balls / BALLS_PER_OVER);
    const ballsInOver = s.balls % BALLS_PER_OVER;
    this.els.overs.textContent = `${overs}.${ballsInOver} / ${this.maxOvers}`;

    const sr = s.balls > 0 ? ((s.runs / s.balls) * 100).toFixed(1) : "0.0";
    this.els.sr.textContent = sr;
  }

  _showBanner(el, text) {
    el.textContent = text;
    el.classList.add("visible");
    clearTimeout(el._hideTimer);
    el._hideTimer = setTimeout(() => el.classList.remove("visible"), 1600);
  }

  _showResult(text, flags = {}) {
    const el = this.els.resultBanner;
    el.textContent = text;
    el.classList.toggle("six", !!flags.six);
    el.classList.toggle("four", !!flags.four);
    el.classList.toggle("wicket", !!flags.wicket);
    if (text) el.classList.add("visible");
    else el.classList.remove("visible");
    clearTimeout(el._hideTimer);
    if (text) {
      el._hideTimer = setTimeout(() => el.classList.remove("visible"), 1800);
    }
  }

  _onResize() {
    const w = window.innerWidth;
    const h = window.innerHeight;
    this.camera.aspect = w / h;
    this.camera.updateProjectionMatrix();
    this.renderer.setSize(w, h, false);
  }

  _animate() {
    requestAnimationFrame(() => this._animate());
    const dt = Math.min(0.05, this.clock.getDelta());

    if (this.state === "between") {
      this.betweenTimer -= dt;
      if (this.betweenTimer <= 0) this._beginDelivery();
    }

    if (this.state === "playing" || this.state === "between") {
      updateBowler(this.bowler, dt, this.deliveryQueue || DELIVERIES.fast, () => {
        this._releaseBall();
      });
      updateSwing(this.batsman, dt);
      this._updateBall(dt);
    } else {
      updateSwing(this.batsman, dt);
    }

    // Follow the ball slightly while it is in flight
    if (this.state === "playing" && this.ballState.active) {
      const lookZ = THREE.MathUtils.clamp(this.ball.position.z * 0.35, -6, 8);
      this.camera.lookAt(0.15, 1.15, lookZ);
    } else {
      this.camera.lookAt(0, 1.2, 0);
    }

    this.renderer.render(this.scene, this.camera);
  }
}
