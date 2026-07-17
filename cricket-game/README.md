# Boundary Blitz — 3D Cricket Batting

A complete browser-based 3D cricket batting game built with Three.js. No backend required.

## How to play

1. Open `index.html` in a modern browser (or serve the folder with any static file server).
2. Choose the number of overs and click **Play**.
3. Face the computer bowler and time your shots:

| Key | Shot |
|-----|------|
| ← | Play to the left |
| → | Play to the right |
| ↑ | Straight drive |
| ↓ | Defensive shot |
| Space | Power shot |

The innings ends after **10 wickets** or when the selected overs are completed.

## Project structure

```
cricket-game/
├── index.html          # Page shell & HUD markup
├── css/style.css       # Scoreboard & overlay styles
└── js/
    ├── main.js         # Entry point
    ├── game.js         # Game loop, physics, scoring
    ├── stadium.js      # Pitch, stands, crowd, lighting, sky
    ├── players.js      # Batsman, bowler, bat swing, ball mesh
    └── audio.js        # Procedural crowd & bat-hit sounds
```

## Notes

- Three.js is loaded from a CDN via an import map (see `index.html`).
- Sound effects are generated with the Web Audio API (no audio files).
- Because ES modules are used, some browsers require the files to be served over HTTP (not `file://`). Example:

```bash
cd cricket-game
python3 -m http.server 8080
```

Then open `http://localhost:8080`.
