# GSAP Elementor Widgets

Adds **5 GSAP-powered Elementor widgets** with full **no-code** controls (dropdowns, sliders, toggles) right in the Elementor panel. Built for **Elementor / Elementor Pro 3.x – 4.x**.

- **Version:** 1.0.0
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
3. Drag any of the 5 widgets onto your page.
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
│       └── class-timeline-reveal.php
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

### 1.0.0
- Initial release with 5 widgets: Animated Heading, Scroll Counter, Parallax Section, Staggered Card Grid, Timeline Reveal.

---

## License
This plugin is licensed under the GPL-2.0+ license. GSAP is loaded from the cdnjs CDN and is subject to its own [standard license](https://gsap.com/community/standard-license/).
