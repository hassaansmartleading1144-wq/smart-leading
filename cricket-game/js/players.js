/**
 * players.js — Procedural batsman & bowler meshes with swing / run-up animation helpers
 */

import * as THREE from "three";

const SKIN = 0xe0b090;
const KIT_BAT = 0x1e4d8c;
const KIT_BOWL = 0xb83227;
const WHITE = 0xf0f0f0;
const WOOD = 0xc4a060;

function makeLimb(w, h, d, color) {
  const mesh = new THREE.Mesh(
    new THREE.BoxGeometry(w, h, d),
    new THREE.MeshStandardMaterial({ color, roughness: 0.7 })
  );
  mesh.castShadow = true;
  return mesh;
}

/**
 * Create a simple humanoid figure. Returns a group with named parts for animation.
 */
function createHumanoid(kitColor, opts = {}) {
  const g = new THREE.Group();

  const torso = makeLimb(0.42, 0.7, 0.28, kitColor);
  torso.position.y = 1.25;
  torso.name = "torso";
  g.add(torso);

  const head = new THREE.Mesh(
    new THREE.SphereGeometry(0.16, 12, 10),
    new THREE.MeshStandardMaterial({ color: SKIN, roughness: 0.65 })
  );
  head.position.y = 1.78;
  head.castShadow = true;
  head.name = "head";
  g.add(head);

  // Helmet (batsman) or cap (bowler)
  if (opts.helmet) {
    const helm = new THREE.Mesh(
      new THREE.SphereGeometry(0.18, 12, 10, 0, Math.PI * 2, 0, Math.PI * 0.55),
      new THREE.MeshStandardMaterial({ color: 0x1a1a1a, roughness: 0.4, metalness: 0.3 })
    );
    helm.position.y = 1.82;
    g.add(helm);
    const grill = new THREE.Mesh(
      new THREE.BoxGeometry(0.22, 0.14, 0.1),
      new THREE.MeshStandardMaterial({ color: 0x333333, metalness: 0.5, roughness: 0.4 })
    );
    grill.position.set(0, 1.72, 0.14);
    g.add(grill);
  } else {
    const cap = new THREE.Mesh(
      new THREE.SphereGeometry(0.17, 12, 8, 0, Math.PI * 2, 0, Math.PI * 0.45),
      new THREE.MeshStandardMaterial({ color: kitColor, roughness: 0.55 })
    );
    cap.position.y = 1.86;
    g.add(cap);
  }

  // Legs
  const legL = makeLimb(0.14, 0.7, 0.16, WHITE);
  legL.position.set(-0.12, 0.45, 0);
  legL.name = "legL";
  g.add(legL);
  const legR = makeLimb(0.14, 0.7, 0.16, WHITE);
  legR.position.set(0.12, 0.45, 0);
  legR.name = "legR";
  g.add(legR);

  // Arms as pivot groups (shoulder → arm)
  const armL = new THREE.Group();
  armL.position.set(-0.28, 1.5, 0);
  armL.name = "armL";
  const armLMesh = makeLimb(0.12, 0.55, 0.12, kitColor);
  armLMesh.position.y = -0.28;
  armL.add(armLMesh);
  g.add(armL);

  const armR = new THREE.Group();
  armR.position.set(0.28, 1.5, 0);
  armR.name = "armR";
  const armRMesh = makeLimb(0.12, 0.55, 0.12, kitColor);
  armRMesh.position.y = -0.28;
  armR.add(armRMesh);
  g.add(armR);

  g.userData.parts = { torso, head, legL, legR, armL, armR };
  return g;
}

/**
 * Cricket bat attached to batsman's right arm.
 */
function createBat() {
  const bat = new THREE.Group();
  bat.name = "bat";

  const blade = new THREE.Mesh(
    new THREE.BoxGeometry(0.14, 0.72, 0.05),
    new THREE.MeshStandardMaterial({ color: WOOD, roughness: 0.55 })
  );
  blade.position.y = -0.55;
  blade.castShadow = true;
  bat.add(blade);

  const shoulder = new THREE.Mesh(
    new THREE.BoxGeometry(0.12, 0.08, 0.05),
    new THREE.MeshStandardMaterial({ color: 0xa88850, roughness: 0.5 })
  );
  shoulder.position.y = -0.18;
  bat.add(shoulder);

  const handle = new THREE.Mesh(
    new THREE.CylinderGeometry(0.025, 0.028, 0.38, 8),
    new THREE.MeshStandardMaterial({ color: 0x222222, roughness: 0.6 })
  );
  handle.position.y = 0.05;
  bat.add(handle);

  // Grip stripe
  const grip = new THREE.Mesh(
    new THREE.CylinderGeometry(0.03, 0.03, 0.2, 8),
    new THREE.MeshStandardMaterial({ color: 0x8b0000, roughness: 0.7 })
  );
  grip.position.y = 0.12;
  bat.add(grip);

  return bat;
}

/**
 * Build the batsman at the crease, facing the bowler (−Z).
 */
export function createBatsman(creaseZ) {
  const root = createHumanoid(KIT_BAT, { helmet: true });
  root.name = "batsman";

  const bat = createBat();
  // Attach bat to right arm; rest pose = bat raised slightly, blade down
  const { armR } = root.userData.parts;
  bat.position.set(0.02, -0.55, 0.05);
  bat.rotation.set(0.15, 0, 0.2);
  armR.add(bat);

  // Stance: side-on, slightly crouched
  root.rotation.y = Math.PI; // face bowler (bowler at −Z)
  root.position.set(0.15, 0, creaseZ - 0.55);
  root.userData.parts.armL.rotation.z = 0.35;
  root.userData.parts.armR.rotation.set(-0.4, 0.2, -0.5);
  root.userData.parts.legL.rotation.x = -0.15;
  root.userData.parts.legR.rotation.x = 0.1;

  root.userData.bat = bat;
  root.userData.swing = {
    active: false,
    t: 0,
    duration: 0.35,
    type: null,
    power: 1,
  };

  return root;
}

/**
 * Build the bowler at the far end.
 */
export function createBowler(bowlerCreaseZ) {
  const root = createHumanoid(KIT_BOWL, { helmet: false });
  root.name = "bowler";
  root.position.set(0.4, 0, bowlerCreaseZ - 8);
  root.rotation.y = 0; // facing +Z toward batsman
  root.userData.runUp = {
    phase: "idle", // idle | runup | gather | release | follow
    t: 0,
    startZ: bowlerCreaseZ - 8,
    releaseZ: bowlerCreaseZ + 0.5,
  };
  return root;
}

/**
 * Cricket ball mesh.
 */
export function createBall() {
  const geo = new THREE.SphereGeometry(0.036, 16, 12);
  const mat = new THREE.MeshStandardMaterial({
    color: 0xb22222,
    roughness: 0.45,
    metalness: 0.15,
  });
  const ball = new THREE.Mesh(geo, mat);
  ball.castShadow = true;
  ball.visible = false;
  ball.name = "ball";

  // Seam
  const seam = new THREE.Mesh(
    new THREE.TorusGeometry(0.036, 0.004, 6, 20),
    new THREE.MeshStandardMaterial({ color: 0xf5f5f5, roughness: 0.8 })
  );
  seam.rotation.x = Math.PI / 2;
  ball.add(seam);

  return ball;
}

/**
 * Start a bat swing animation for a given shot type.
 * Types: left | right | straight | defense | power
 */
export function startSwing(batsman, type, power = 1) {
  const swing = batsman.userData.swing;
  if (swing.active) return false;
  swing.active = true;
  swing.t = 0;
  swing.type = type;
  swing.power = power;
  swing.duration = type === "defense" ? 0.28 : type === "power" ? 0.42 : 0.34;
  swing.hitWindowStart = 0.35;
  swing.hitWindowEnd = 0.72;
  swing.hasHitOpportunity = true;
  return true;
}

/**
 * Advance swing animation. Returns true while swing is in the "sweet spot" window.
 */
export function updateSwing(batsman, dt) {
  const swing = batsman.userData.swing;
  const { armR, armL, torso } = batsman.userData.parts;
  const bat = batsman.userData.bat;

  if (!swing.active) {
    // Idle micro-motion
    const idle = Math.sin(performance.now() * 0.003) * 0.03;
    armR.rotation.set(-0.4 + idle, 0.2, -0.5);
    return false;
  }

  swing.t += dt / swing.duration;
  const t = Math.min(1, swing.t);

  // Ease: backswing → through → follow-through
  const back = t < 0.35 ? t / 0.35 : 1;
  const through = t < 0.35 ? 0 : Math.min(1, (t - 0.35) / 0.45);
  const follow = t < 0.8 ? 0 : (t - 0.8) / 0.2;

  const type = swing.type;
  let rx = -0.4;
  let ry = 0.2;
  let rz = -0.5;
  let batRx = 0.15;
  let batRy = 0;
  let batRz = 0.2;
  let torsoY = 0;

  if (type === "left") {
    rx = -0.4 - back * 0.8 + through * 1.6 + follow * 0.3;
    ry = 0.2 - through * 0.9;
    rz = -0.5 - through * 0.8;
    batRz = 0.2 + through * 1.2;
    torsoY = -through * 0.35;
  } else if (type === "right") {
    rx = -0.4 - back * 0.7 + through * 1.5;
    ry = 0.2 + through * 0.85;
    rz = -0.5 + through * 0.6;
    batRz = 0.2 - through * 1.1;
    torsoY = through * 0.35;
  } else if (type === "straight" || type === "power") {
    const p = type === "power" ? 1.25 : 1;
    rx = -0.4 - back * 1.0 * p + through * 2.0 * p;
    ry = 0.2 + through * 0.15;
    rz = -0.5 + through * 0.2;
    batRx = 0.15 - through * 0.9;
    torsoY = 0;
  } else if (type === "defense") {
    rx = -0.4 - back * 0.3 + through * 0.55;
    ry = 0.2;
    rz = -0.5 + through * 0.15;
    batRx = 0.15 + through * 0.4;
  }

  armR.rotation.set(rx, ry, rz);
  armL.rotation.z = 0.35 + through * 0.4;
  bat.rotation.set(batRx, batRy, batRz);
  torso.rotation.y = torsoY;

  const inWindow = t >= swing.hitWindowStart && t <= swing.hitWindowEnd;

  if (t >= 1) {
    swing.active = false;
    swing.hasHitOpportunity = false;
    // Reset toward stance
    armR.rotation.set(-0.4, 0.2, -0.5);
    bat.rotation.set(0.15, 0, 0.2);
    torso.rotation.y = 0;
  }

  return inWindow && swing.hasHitOpportunity;
}

/**
 * Animate bowler run-up / delivery. Calls onRelease once at ball release.
 */
export function updateBowler(bowler, dt, delivery, onRelease) {
  const run = bowler.userData.runUp;
  const { legL, legR, armR, armL, torso } = bowler.userData.parts;

  if (run.phase === "idle") return;

  run.t += dt;

  if (run.phase === "runup") {
    const speed = delivery.runSpeed;
    bowler.position.z += speed * dt;
    const stride = Math.sin(run.t * speed * 2.2);
    legL.rotation.x = stride * 0.7;
    legR.rotation.x = -stride * 0.7;
    armL.rotation.x = -stride * 0.5;
    armR.rotation.x = stride * 0.5;

    if (bowler.position.z >= run.releaseZ - 2.2) {
      run.phase = "gather";
      run.t = 0;
    }
  } else if (run.phase === "gather") {
    // Bound into gather, arms wind up
    const g = Math.min(1, run.t / 0.28);
    armR.rotation.x = -Math.PI * 0.9 * g;
    armL.rotation.x = 0.4 * g;
    torso.rotation.x = -0.15 * g;
    bowler.position.y = Math.sin(g * Math.PI) * 0.15;
    if (g >= 1) {
      run.phase = "release";
      run.t = 0;
    }
  } else if (run.phase === "release") {
    const g = Math.min(1, run.t / 0.18);
    armR.rotation.x = -Math.PI * 0.9 + g * Math.PI * 1.4;
    armL.rotation.x = 0.4 - g * 0.3;
    torso.rotation.x = -0.15 + g * 0.25;
    bowler.position.y = 0;
    if (g >= 0.55 && !run.released) {
      run.released = true;
      onRelease();
    }
    if (g >= 1) {
      run.phase = "follow";
      run.t = 0;
    }
  } else if (run.phase === "follow") {
    bowler.position.z += 1.5 * dt;
    armR.rotation.x = Math.PI * 0.5 * (1 - Math.min(1, run.t));
    if (run.t > 1.2) {
      run.phase = "idle";
      // Reset for next ball
      bowler.position.set(0.4 + (Math.random() - 0.5) * 0.3, 0, run.startZ);
      armR.rotation.set(0, 0, 0);
      armL.rotation.set(0, 0, 0);
      legL.rotation.set(0, 0, 0);
      legR.rotation.set(0, 0, 0);
      torso.rotation.set(0, 0, 0);
      run.released = false;
      run.t = 0;
    }
  }
}

export function startBowlerRunUp(bowler) {
  const run = bowler.userData.runUp;
  run.phase = "runup";
  run.t = 0;
  run.released = false;
  bowler.position.set(0.4 + (Math.random() - 0.5) * 0.25, 0, run.startZ);
}
