/**
 * audio.js — Procedural sound effects via Web Audio API
 * (no external audio files required)
 */

let audioCtx = null;

function getCtx() {
  if (!audioCtx) {
    audioCtx = new (window.AudioContext || window.webkitAudioContext)();
  }
  if (audioCtx.state === "suspended") {
    audioCtx.resume();
  }
  return audioCtx;
}

/** Soft crowd murmur loop (ambient) */
export function startCrowdAmbience() {
  const ctx = getCtx();
  const duration = 2.5;
  const buffer = ctx.createBuffer(2, ctx.sampleRate * duration, ctx.sampleRate);

  for (let ch = 0; ch < 2; ch++) {
    const data = buffer.getChannelData(ch);
    let last = 0;
    for (let i = 0; i < data.length; i++) {
      const white = Math.random() * 2 - 1;
      // Brown-ish noise for distant crowd texture
      last = (last + 0.02 * white) / 1.02;
      data[i] = last * 0.35;
    }
  }

  const source = ctx.createBufferSource();
  source.buffer = buffer;
  source.loop = true;

  const filter = ctx.createBiquadFilter();
  filter.type = "lowpass";
  filter.frequency.value = 800;

  const gain = ctx.createGain();
  gain.gain.value = 0.08;

  source.connect(filter);
  filter.connect(gain);
  gain.connect(ctx.destination);
  source.start();

  return { source, gain };
}

/** Cheer swell for boundaries / sixes */
export function playCrowdCheer(intensity = 1) {
  const ctx = getCtx();
  const now = ctx.currentTime;
  const duration = 1.4 + intensity * 0.6;

  const buffer = ctx.createBuffer(1, ctx.sampleRate * duration, ctx.sampleRate);
  const data = buffer.getChannelData(0);
  let last = 0;
  for (let i = 0; i < data.length; i++) {
    const t = i / data.length;
    const env = Math.sin(Math.PI * Math.min(1, t * 1.4)) * (0.5 + 0.5 * intensity);
    const white = Math.random() * 2 - 1;
    last = (last + 0.04 * white) / 1.04;
    data[i] = last * env * 1.8;
  }

  const source = ctx.createBufferSource();
  source.buffer = buffer;

  const filter = ctx.createBiquadFilter();
  filter.type = "bandpass";
  filter.frequency.value = 900 + intensity * 400;
  filter.Q.value = 0.7;

  const gain = ctx.createGain();
  gain.gain.setValueAtTime(0.0001, now);
  gain.gain.exponentialRampToValueAtTime(0.22 * intensity, now + 0.08);
  gain.gain.exponentialRampToValueAtTime(0.0001, now + duration);

  source.connect(filter);
  filter.connect(gain);
  gain.connect(ctx.destination);
  source.start();
}

/** Bat / ball contact crack */
export function playBatHit(power = 1) {
  const ctx = getCtx();
  const now = ctx.currentTime;

  // Transient noise burst
  const noiseDur = 0.08;
  const noiseBuf = ctx.createBuffer(1, ctx.sampleRate * noiseDur, ctx.sampleRate);
  const nData = noiseBuf.getChannelData(0);
  for (let i = 0; i < nData.length; i++) {
    nData[i] = (Math.random() * 2 - 1) * (1 - i / nData.length);
  }
  const noise = ctx.createBufferSource();
  noise.buffer = noiseBuf;

  const noiseFilter = ctx.createBiquadFilter();
  noiseFilter.type = "highpass";
  noiseFilter.frequency.value = 1200;

  const noiseGain = ctx.createGain();
  noiseGain.gain.setValueAtTime(0.35 * power, now);
  noiseGain.gain.exponentialRampToValueAtTime(0.0001, now + noiseDur);

  noise.connect(noiseFilter);
  noiseFilter.connect(noiseGain);
  noiseGain.connect(ctx.destination);
  noise.start(now);

  // Woody tonal thump
  const osc = ctx.createOscillator();
  osc.type = "triangle";
  osc.frequency.setValueAtTime(180 + power * 40, now);
  osc.frequency.exponentialRampToValueAtTime(60, now + 0.12);

  const oscGain = ctx.createGain();
  oscGain.gain.setValueAtTime(0.28 * power, now);
  oscGain.gain.exponentialRampToValueAtTime(0.0001, now + 0.14);

  osc.connect(oscGain);
  oscGain.connect(ctx.destination);
  osc.start(now);
  osc.stop(now + 0.16);
}

/** Soft disappointment murmur for wickets / dots */
export function playCrowdOoh() {
  const ctx = getCtx();
  const now = ctx.currentTime;
  const duration = 0.9;

  const buffer = ctx.createBuffer(1, ctx.sampleRate * duration, ctx.sampleRate);
  const data = buffer.getChannelData(0);
  let last = 0;
  for (let i = 0; i < data.length; i++) {
    const t = i / data.length;
    const env = Math.sin(Math.PI * t);
    last = (last + 0.03 * (Math.random() * 2 - 1)) / 1.03;
    data[i] = last * env;
  }

  const source = ctx.createBufferSource();
  source.buffer = buffer;
  const filter = ctx.createBiquadFilter();
  filter.type = "lowpass";
  filter.frequency.value = 500;
  const gain = ctx.createGain();
  gain.gain.value = 0.12;

  source.connect(filter);
  filter.connect(gain);
  gain.connect(ctx.destination);
  source.start();
}

/** Short stump rattle for bowled / edged wickets */
export function playStumpHit() {
  const ctx = getCtx();
  const now = ctx.currentTime;

  for (let i = 0; i < 3; i++) {
    const osc = ctx.createOscillator();
    osc.type = "square";
    osc.frequency.value = 400 + i * 90 + Math.random() * 40;
    const gain = ctx.createGain();
    const t = now + i * 0.03;
    gain.gain.setValueAtTime(0.08, t);
    gain.gain.exponentialRampToValueAtTime(0.0001, t + 0.1);
    osc.connect(gain);
    gain.connect(ctx.destination);
    osc.start(t);
    osc.stop(t + 0.12);
  }
}

export function unlockAudio() {
  getCtx();
}
