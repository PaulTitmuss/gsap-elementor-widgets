# GSAP Elementor Widgets

Adds **9 GSAP-powered Elementor widgets** with full **no-code** controls (dropdowns, sliders, toggles) right in the Elementor panel. Built for **Elementor / Elementor Pro 3.x – 4.x**.

- **Version:** 1.1.0
- **Requires WordPress:** 6.0+
- **Requires PHP:** 7.4+
- **Requires Plugins:** Elementor (3.0.0+)
- **License:** GPL-2.0+

---

## Widgets included

All widgets appear in a dedicated **GSAP Animations** category in the Elementor widget panel.

### 1. Animated Heading
Text reveal animations with no code.
- **Animation type:** Fade In, Slide Up, Split Text (letter-by-letter), Typewriter
- **Duration** & **Delay** sliders
- **Easing:** Linear, Power1–4, Elastic, Bounce, Back, Circ, Expo, Sine
- **Trigger:** On Page Load or On Scroll Into View
- **Repeat** on every scroll (toggle)
- Full typography, color, alignment and link controls
- Optional per-letter **stagger** for Split Text

### 2. Scroll Counter
A number that counts up when scrolled into view (GSAP ScrollTrigger + `gsap.to`).
- **Start** and **End** numbers, decimal places
- **Duration** slider
- **Prefix** (e.g. `$`) and **Suffix** (e.g. `%`, `k+`)
- **Number formatting** with a configurable thousands separator
- **Font size**, **color**, **alignment**, full typography
- **Repeat** on every scroll (toggle)

### 3. Parallax Section
A wrapper widget that applies a parallax scrolling effect.
- **Apply to:** Background Image or Content
- **Parallax speed** slider (-1 to 1, where 0 = no movement)
- **Direction:** Vertical or Horizontal
- **Scrub** (tie animation to the scrollbar) toggle
- **Element height** (px / vh)
- Overlay color, content color and alignment controls

### 4. Staggered Card Grid
A grid of repeatable cards that animate in one-by-one on scroll (GSAP `stagger`).
- Repeatable cards: **image, subtitle, title, description, button + link**
- **Columns:** 1 / 2 / 3 / 4 (responsive)
- **Animation type:** Fade Up, Fade In, Zoom In, Slide From Left, Slide From Right
- **Stagger delay** & **Duration** sliders, easing dropdown
- Card styling: background color, border radius, padding, box shadow, colors
- **Repeat** on every scroll (toggle)

### 5. Timeline Reveal
A vertical or horizontal timeline whose milestones animate in sequence on scroll.
- Repeatable items: **icon (Elementor icon picker), date/label, title, description**
- **Layout:** Vertical or Horizontal
- **Animation direction:** Alternating (left/right) or all from one side
- **Connector line style:** Solid / Dashed / Dotted, with line color
- **Dot color**, **icon color**, title/label/description colors
- **Animation duration** & **stagger delay** sliders
- **Repeat** on every scroll (toggle)

### 6. Animated Text
Reveal longer body copy / paragraphs with GSAP — the text-focused companion to Animated Heading.
- **Rich text (WYSIWYG)** content
- **Animation type:** Fade In, Slide Up, Slide From Left, Slide From Right, Blur In, Word-by-Word, Line-by-Line, Character-by-Character
- **Duration**, **Delay** and **Stagger** sliders (stagger applies to word/line/char modes)
- **Easing:** Linear, Power1–4, Back, Elastic, Bounce, Circ, Expo, Sine
- **Trigger:** On Page Load or On Scroll Into View
- **Repeat** on every scroll (toggle)
- Full typography, color and alignment (incl. justify) controls

### 7. 3D Icon Box
An icon + title + description box that enters with an eye-catching 3D transform.
- **Icon** (Elementor icon picker), title (with HTML tag), description, optional link
- **3D entrance:** Flip In X, Flip In Y, Rotate In 3D, Zoom + Rotate, Swing In, Unfold
- **Perspective** slider (controls how dramatic the 3D effect is)
- **Duration**, **Delay**, easing, trigger (load / scroll), repeat toggle
- Optional **3D hover tilt** that follows the cursor
- Box styling: background, padding, radius, box shadow, alignment
- Icon styling: color, background, size, container size & radius; title/description colors + typography

### 8. Reveal on Scroll
A versatile wrapper that reveals **text or an image** on scroll with clip/mask-style wipes.
- **Content type:** Image (media control) or Text/Heading (with HTML tag)
- **Reveal type:** Clip Wipe, Fade + Slide, Zoom Reveal, Blur Reveal
- **Direction:** From Left / Right / Top / Bottom (clip & fade-slide)
- **Duration**, **Delay**, easing
- **Start when** dropdown (how far into the viewport before revealing)
- **Tie to scroll (scrub)** — progress the reveal with the scrollbar
- **Repeat** on every scroll (toggle)
- Image styling (width, radius, shadow) or text styling (color, typography, alignment)

### 9. SVG Animator
Animates inline SVG markup you paste in — including a free self-drawing line effect.
- **SVG markup** code field (paste from `<svg>` to `</svg>`; safely sanitised)
- **Animation mode:** Draw (self-drawing lines), Fade + Scale In, Fade Paths Sequentially, Rotate In
- **Draw** uses the stroke-dasharray technique — no premium GSAP plugin required
- **Fade in fill after draw** toggle (for SVGs that use fills)
- **Duration**, **Stagger between shapes**, **Delay** sliders, easing
- **Trigger:** On Page Load or On Scroll Into View
- **Loop continuously** toggle and **Repeat** on every scroll (toggle)
- Style: width, alignment, optional stroke color & stroke width overrides

---

## Installation

### From the WordPress admin (recommended)
1. Zip the `gsap-elementor-widgets` folder so the archive contains
   `gsap-elementor-widgets/gsap-elementor-widgets.php` at its root.
2. In WordPress admin, go to **Plugins → Add New → Upload Plugin**.
3. Choose the zip file and click **Install Now**, then **Activate**.
4. Make sure **Elementor** (and optionally **Elementor Pro**) is installed and active.

### Manual (FTP / file manager)
1. Copy the `gsap-elementor-widgets` folder into `wp-content/plugins/`.
2. Go to **Plugins** in WordPress admin and activate **GSAP Elementor Widgets**.

> If Elementor is not active, the plugin stays dormant and shows an admin notice asking you to install/activate Elementor. Nothing else loads until Elementor is present.

---

## Usage

1. Edit any page with Elementor.
2. In the widget panel, find the **GSAP Animations** category.
3. Drag any of the 9 widgets onto your page.
4. Configure the animation entirely through the panel controls — no code required.
5. Preview / publish. Animations fire on page load or as the section scrolls into view.

---

## How it works (technical notes)

- **GSAP** (`3.12.5`), **ScrollTrigger** and **TextPlugin** are loaded from the cdnjs CDN.
- Scripts are **registered** globally but only **enqueued on pages where a widget is actually used**, via each widget's `get_script_depends()` / `get_style_depends()`. Pages without these widgets stay lightweight.
- Each widget's `render()` outputs HTML with a `data-gsap-type` attribute plus a JSON `data-gsap` config attribute.
- `assets/js/gsap-widgets-frontend.js` reads those attributes and initialises the correct GSAP animation. All logic is wrapped in a `GSAPElementorWidgets` namespace to avoid global conflicts, and boots on `DOMContentLoaded`.
- Widgets are registered with the current Elementor API: `add_action('elementor/widgets/register', ...)` and `add_action('elementor/elements/categories_registered', ...)`.
- The script also hooks into `elementor/frontend/element_ready/*` so animations render correctly inside the Elementor editor preview.

### File structure
```
gsap-elementor-widgets/
├── gsap-elementor-widgets.php          Main plugin file (header + bootstrap)
├── README.md
├── includes/
│   ├── class-plugin.php               Main plugin class (registers everything)
│   └── widgets/
│       ├── class-animated-heading.php
│       ├── class-scroll-counter.php
│       ├── class-parallax-section.php
│       ├── class-staggered-grid.php
│       ├── class-timeline-reveal.php
│       ├── class-animated-text.php
│       ├── class-icon-box-3d.php
│       ├── class-reveal-on-scroll.php
│       └── class-svg-animator.php
└── assets/
    ├── js/
    │   └── gsap-widgets-frontend.js   All GSAP initialisation logic
    └── css/
        └── gsap-widgets.css           Widget styling
```

---

## Frequently asked questions

**Does this require Elementor Pro?**
No. It works with free Elementor 3.0+. It is also fully compatible with Elementor Pro 3.x / 4.x.

**Can I self-host GSAP instead of using the CDN?**
Yes — drop the GSAP files into `assets/js/` and update the `wp_register_script` URLs in `includes/class-plugin.php`.

**Will the animations replay every time I scroll?**
Only if you enable the **Repeat On Every Scroll** toggle on that widget. Otherwise they play once.

---

## Changelog

### 1.1.0
- Added 4 new widgets: **Animated Text**, **3D Icon Box**, **Reveal on Scroll** (text + images), and **SVG Animator** (free self-drawing lines).
- Plugin now ships **9 widgets** in total.

### 1.0.0
- Initial release with 5 widgets: Animated Heading, Scroll Counter, Parallax Section, Staggered Card Grid, Timeline Reveal.

---

## License
This plugin is licensed under the GPL-2.0+ license. GSAP is loaded from the cdnjs CDN and is subject to its own [standard license](https://gsap.com/community/standard-license/).
