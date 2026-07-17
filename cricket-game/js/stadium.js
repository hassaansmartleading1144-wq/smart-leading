/**
 * stadium.js — Builds the cricket ground: pitch, outfield, stands, crowd, sky, lights
 */

import * as THREE from "three";

const PITCH_LEN = 20.12; // scaled metres (crease-to-crease ~20.12)
const PITCH_WID = 3.05;

/**
 * Create sky dome with gradient colors.
 */
function createSky() {
  const geo = new THREE.SphereGeometry(220, 32, 24);
  const canvas = document.createElement("canvas");
  canvas.width = 4;
  canvas.height = 256;
  const ctx = canvas.getContext("2d");
  const grad = ctx.createLinearGradient(0, 0, 0, 256);
  grad.addColorStop(0, "#1a3a6e");
  grad.addColorStop(0.35, "#4a8ec8");
  grad.addColorStop(0.62, "#8ec5e8");
  grad.addColorStop(0.78, "#c8dff0");
  grad.addColorStop(1, "#e8f0d8");
  ctx.fillStyle = grad;
  ctx.fillRect(0, 0, 4, 256);

  const tex = new THREE.CanvasTexture(canvas);
  const mat = new THREE.MeshBasicMaterial({
    map: tex,
    side: THREE.BackSide,
    depthWrite: false,
  });
  return new THREE.Mesh(geo, mat);
}

/**
 * Outfield grass disc with subtle color variation.
 */
function createOutfield() {
  const geo = new THREE.CircleGeometry(85, 64);
  const canvas = document.createElement("canvas");
  canvas.width = 512;
  canvas.height = 512;
  const ctx = canvas.getContext("2d");
  ctx.fillStyle = "#2d7a3a";
  ctx.fillRect(0, 0, 512, 512);
  for (let i = 0; i < 4000; i++) {
    const x = Math.random() * 512;
    const y = Math.random() * 512;
    const shade = 35 + Math.floor(Math.random() * 40);
    ctx.fillStyle = `rgb(${shade},${90 + shade},${40 + shade * 0.4})`;
    ctx.fillRect(x, y, 2 + Math.random() * 3, 2 + Math.random() * 3);
  }
  // Boundary rope ring hint
  ctx.strokeStyle = "rgba(220, 60, 50, 0.55)";
  ctx.lineWidth = 6;
  ctx.beginPath();
  ctx.arc(256, 256, 230, 0, Math.PI * 2);
  ctx.stroke();

  const tex = new THREE.CanvasTexture(canvas);
  tex.colorSpace = THREE.SRGBColorSpace;
  const mat = new THREE.MeshStandardMaterial({
    map: tex,
    roughness: 0.92,
    metalness: 0.02,
  });
  const mesh = new THREE.Mesh(geo, mat);
  mesh.rotation.x = -Math.PI / 2;
  mesh.receiveShadow = true;
  return mesh;
}

/**
 * Cricket pitch strip with creases and markings.
 */
function createPitch() {
  const group = new THREE.Group();

  const pitchGeo = new THREE.BoxGeometry(PITCH_WID, 0.08, PITCH_LEN + 4);
  const pitchMat = new THREE.MeshStandardMaterial({
    color: 0xc4a574,
    roughness: 0.85,
    metalness: 0.05,
  });
  const pitch = new THREE.Mesh(pitchGeo, pitchMat);
  pitch.position.y = 0.04;
  pitch.receiveShadow = true;
  pitch.castShadow = true;
  group.add(pitch);

  // Crease lines (white)
  const lineMat = new THREE.MeshBasicMaterial({ color: 0xf5f5f0 });
  const makeLine = (w, d, x, z) => {
    const m = new THREE.Mesh(new THREE.BoxGeometry(w, 0.02, d), lineMat);
    m.position.set(x, 0.09, z);
    group.add(m);
  };

  // Popping crease batsman end (near camera, +Z)
  const batsmanCreaseZ = PITCH_LEN / 2;
  const bowlerCreaseZ = -PITCH_LEN / 2;
  makeLine(PITCH_WID + 0.8, 0.06, 0, batsmanCreaseZ);
  makeLine(PITCH_WID + 0.8, 0.06, 0, bowlerCreaseZ);
  // Return creases
  makeLine(0.06, 1.22, -PITCH_WID / 2 - 0.1, batsmanCreaseZ);
  makeLine(0.06, 1.22, PITCH_WID / 2 + 0.1, batsmanCreaseZ);
  makeLine(0.06, 1.22, -PITCH_WID / 2 - 0.1, bowlerCreaseZ);
  makeLine(0.06, 1.22, PITCH_WID / 2 + 0.1, bowlerCreaseZ);

  group.userData = { batsmanCreaseZ, bowlerCreaseZ, PITCH_LEN, PITCH_WID };
  return group;
}

/**
 * Three stumps + bails at a crease.
 */
export function createStumps(z) {
  const group = new THREE.Group();
  const stumpMat = new THREE.MeshStandardMaterial({
    color: 0xe8dcc8,
    roughness: 0.55,
    metalness: 0.05,
  });
  const bailMat = new THREE.MeshStandardMaterial({
    color: 0xd4c4a8,
    roughness: 0.5,
  });

  const positions = [-0.12, 0, 0.12];
  positions.forEach((x) => {
    const stump = new THREE.Mesh(
      new THREE.CylinderGeometry(0.025, 0.028, 0.71, 10),
      stumpMat
    );
    stump.position.set(x, 0.355, z);
    stump.castShadow = true;
    stump.name = "stump";
    group.add(stump);
  });

  const bail = new THREE.Mesh(new THREE.BoxGeometry(0.28, 0.03, 0.04), bailMat);
  bail.position.set(0, 0.73, z);
  bail.castShadow = true;
  bail.name = "bail";
  group.add(bail);

  group.position.y = 0.08;
  return group;
}

/**
 * Stadium stands as tiered rings of seats + simple crowd figures.
 */
function createStands() {
  const group = new THREE.Group();
  const standMat = new THREE.MeshStandardMaterial({
    color: 0x3a4550,
    roughness: 0.8,
  });

  // Tiered seating rings (torus approximations via lathe-like cylinder shells)
  for (let tier = 0; tier < 4; tier++) {
    const radius = 90 + tier * 6.5;
    const ring = new THREE.Mesh(
      new THREE.TorusGeometry(radius, 2.4, 8, 64),
      standMat
    );
    ring.rotation.x = Math.PI / 2;
    ring.position.y = 2.2 + tier * 3.1;
    ring.receiveShadow = true;
    group.add(ring);

    // Back wall for each tier
    const wall = new THREE.Mesh(
      new THREE.CylinderGeometry(radius + 2.8, radius + 2.8, 3.2, 64, 1, true),
      new THREE.MeshStandardMaterial({
        color: 0x2a3340,
        roughness: 0.85,
        side: THREE.DoubleSide,
      })
    );
    wall.position.y = 3.5 + tier * 3.1;
    group.add(wall);
  }

  // Crowd — instanced colored capsules for performance
  const crowdColors = [0xc0392b, 0x2980b9, 0xf1c40f, 0xffffff, 0x27ae60, 0x8e44ad, 0xe67e22];
  const bodyGeo = new THREE.CapsuleGeometry(0.25, 0.45, 4, 6);
  const count = 900;
  const crowd = new THREE.InstancedMesh(
    bodyGeo,
    new THREE.MeshStandardMaterial({ roughness: 0.7, metalness: 0.05 }),
    count
  );

  const dummy = new THREE.Object3D();
  const color = new THREE.Color();
  for (let i = 0; i < count; i++) {
    const angle = Math.random() * Math.PI * 2;
    const tier = Math.floor(Math.random() * 4);
    const radius = 90 + tier * 6.5 + (Math.random() - 0.5) * 2;
    const y = 3.4 + tier * 3.1 + Math.random() * 0.35;
    dummy.position.set(Math.cos(angle) * radius, y, Math.sin(angle) * radius);
    dummy.scale.setScalar(0.7 + Math.random() * 0.5);
    dummy.lookAt(0, y, 0);
    dummy.updateMatrix();
    crowd.setMatrixAt(i, dummy.matrix);
    color.setHex(crowdColors[i % crowdColors.length]);
    crowd.setColorAt(i, color);
  }
  crowd.instanceMatrix.needsUpdate = true;
  if (crowd.instanceColor) crowd.instanceColor.needsUpdate = true;
  group.add(crowd);
  group.userData.crowd = crowd;

  // Floodlight towers
  const poleMat = new THREE.MeshStandardMaterial({ color: 0x888888, metalness: 0.6, roughness: 0.4 });
  for (let i = 0; i < 4; i++) {
    const a = (i / 4) * Math.PI * 2 + Math.PI / 4;
    const tower = new THREE.Group();
    const pole = new THREE.Mesh(new THREE.CylinderGeometry(0.35, 0.5, 28, 8), poleMat);
    pole.position.y = 14;
    pole.castShadow = true;
    tower.add(pole);
    const head = new THREE.Mesh(
      new THREE.BoxGeometry(4, 1.2, 1.5),
      new THREE.MeshStandardMaterial({ color: 0xdddddd, emissive: 0xfff2cc, emissiveIntensity: 0.6 })
    );
    head.position.y = 28;
    tower.add(head);
    tower.position.set(Math.cos(a) * 78, 0, Math.sin(a) * 78);
    group.add(tower);

    const light = new THREE.SpotLight(0xfff5e0, 80, 160, Math.PI / 5, 0.4, 1.2);
    light.position.set(Math.cos(a) * 78, 28, Math.sin(a) * 78);
    light.target.position.set(0, 0, 0);
    light.castShadow = i < 2;
    light.shadow.mapSize.set(1024, 1024);
    group.add(light);
    group.add(light.target);
  }

  // Advertising boards
  for (let i = 0; i < 16; i++) {
    const a = (i / 16) * Math.PI * 2;
    const board = new THREE.Mesh(
      new THREE.BoxGeometry(12, 2.2, 0.3),
      new THREE.MeshStandardMaterial({ roughness: 0.5 })
    );
    board.material.color.setHSL((i * 0.07) % 1, 0.55, 0.35);
    board.position.set(Math.cos(a) * 72, 1.2, Math.sin(a) * 72);
    board.lookAt(0, 1.2, 0);
    group.add(board);
  }

  return group;
}

/**
 * Assemble the full stadium scene content.
 */
export function buildStadium(scene) {
  const root = new THREE.Group();
  root.name = "stadium";

  root.add(createSky());
  root.add(createOutfield());

  const pitch = createPitch();
  root.add(pitch);

  const batsmanStumps = createStumps(pitch.userData.batsmanCreaseZ);
  const bowlerStumps = createStumps(pitch.userData.bowlerCreaseZ);
  root.add(batsmanStumps);
  root.add(bowlerStumps);

  root.add(createStands());

  // Ambient + hemisphere for soft daylight fill
  const hemi = new THREE.HemisphereLight(0xb1d4ff, 0x3a5a28, 0.85);
  root.add(hemi);
  const sun = new THREE.DirectionalLight(0xfff2d6, 1.35);
  sun.position.set(40, 60, 20);
  sun.castShadow = true;
  sun.shadow.mapSize.set(2048, 2048);
  sun.shadow.camera.near = 10;
  sun.shadow.camera.far = 160;
  sun.shadow.camera.left = -50;
  sun.shadow.camera.right = 50;
  sun.shadow.camera.top = 50;
  sun.shadow.camera.bottom = -50;
  root.add(sun);

  scene.add(root);

  return {
    root,
    pitch,
    batsmanStumps,
    bowlerStumps,
    dims: pitch.userData,
  };
}

export { PITCH_LEN, PITCH_WID };
