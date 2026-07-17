/**
 * main.js — Entry point for Boundary Blitz 3D Cricket
 *
 * Loads the game once the DOM canvas is ready. All gameplay lives in game.js;
 * stadium construction, players, and audio are separate modules for clarity.
 */

import { CricketGame } from "./game.js";

const canvas = document.getElementById("game-canvas");

if (!canvas) {
  console.error("Missing #game-canvas element.");
} else {
  try {
    // Start rendering the stadium immediately; match begins from the start screen.
    // eslint-disable-next-line no-new
    new CricketGame(canvas);
  } catch (err) {
    console.error("Failed to start cricket game:", err);
  }
}
