# GSAP Elementor Widgets

Adds **11 GSAP-powered Elementor widgets** with full **no-code** controls (dropdowns, sliders, toggles) right in the Elementor panel. Built for **Elementor / Elementor Pro 3.x – 4.x**.

- **Version:** 1.2.6
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
- **Reveal Direction** (word/line/char modes): From Below, From Above, From Left, From Right, Scale Up, or Fade Only
- **Reveal Order** (word/line/char modes): Left→Right, Right→Left, From the Centre, From the Edges, or Random
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

### 10. Hero to Bento Scroll
A full-screen hero (video, image or solid colour background) that shrinks and settles into its place within a **bento grid** of smaller cards as the visitor scrolls — inspired by the hero on elementor.com. Powered by GSAP ScrollTrigger pinning.
- **Background type:** Video (paste a **YouTube**, **Vimeo** or direct **.mp4** link — autoplays muted + loops, scaled to cover with no black bars), Image, or Solid Colour
- **Hero content:** heading, sub heading and an optional button with link
- **Bento cards:** repeatable cards, each with an image, title and text
- **Layout:** grid columns (2–4), hero width/height in grid cells, gap, max width, grid height
- **Scroll length** slider — controls how long/drawn-out the shrink effect lasts
- **Card easing** dropdown, staggered card reveal
- **Bento card layouts:** each card can be *Image Above Text* or *Image Fills Card* (text centred over the image), with an optional per-card image overlay tint and an optional per-card button + link
- **Animated hero overlay:** separate *Start* (full screen) and *Final* (shrunk) overlay colours that fade as the hero shrinks — e.g. start transparent so a video plays clean, then fade a tint in
- **Fade in text & button on scroll** toggle — hide the heading/sub-heading/button initially and fade them in as the hero shrinks
- Style: hero heading/sub-heading/button colours & typography, card background, corner radius, card title & text **colours *and* typography**, and full card **button styling** (text/background colours, hover colours, typography, corner radius, padding) — matching the hero controls
- Automatically disables the scroll effect for reduced-motion visitors and inside the Elementor editor (shows the final grid instead)

### 11. Motion Path
Moves an **image, icon or inline SVG** along a curved path using GSAP's MotionPathPlugin — the object can glide as the visitor scrolls, or play on its own.
- **Moving object:** choose an Image, an Icon (Elementor icon picker) or paste inline SVG; set its size and (for icons/SVG) colour
- **Ready-made path shapes:** Wave, Rolling Hills, Arc (rainbow), Loop (circle), S-Curve, Zig-Zag, Diagonal — or **Custom** (paste your own SVG path `d` data drawn on a 1000×500 canvas)
- **Travel in reverse** toggle and **Rotate to follow path** toggle (great for a rocket, plane or arrow that points where it's going)
- **Show the path line** toggle with line colour, thickness and style (solid / dashed / dotted)
- **Trigger:** Scroll (scrub — the object moves along the path as you scroll, and back as you scroll up), On Scroll Into View, or On Page Load
- **Duration**, **Delay**, **easing**, **Loop continuously** (with optional **yo-yo**), **Replay on every scroll**, and a **Scroll length** slider for the scrub effect
- Style: stage height, background colour, corner radius
- Automatically shows the object resting at the start of the path for reduced-motion visitors

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
3. Drag any of the 11 widgets onto your page. (The **GSAP Animations** category now appears at the very top of the widget panel for quick access.)
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
│       ├── class-svg-animator.php
│       ├── class-hero-bento.php
│       └── class-motion-path.php
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

### 1.2.6
- **New widget: Motion Path.** Move an image, icon or inline SVG along a curved path — a wave, rolling hills, an arc, a loop, an S-curve, a zig-zag, a diagonal, or your own custom path. It can travel as the visitor scrolls (scrub), play when scrolled into view, or play on page load (with optional looping and yo-yo). Options include *Rotate to follow path* (so a rocket/plane/arrow points where it's going), *Travel in reverse*, and a *Show the path line* toggle with colour, thickness and solid/dashed/dotted styles. Built on GSAP's MotionPathPlugin.
- **Added text & button styling to the smaller Bento cards**, matching the hero. The Hero to Bento Scroll widget's **Card Style** section now includes **typography** for the card title and body text, plus full **button styling** — button typography, hover text/background colours, corner radius and padding — on top of the existing button colours. (No change is needed to existing pages; the new controls simply give you more options.)

### 1.2.5
- **Big Hero to Bento Scroll upgrade — more control over the cards and hero overlay:**
  - **Buttons on every card.** Each Bento card now has its own optional **Button Text** and **Button Link** (leave the text blank for no button). Style them with the new **Card Button Text Colour** and **Card Button Background** controls in the Card Style tab.
  - **Two card layouts.** Each card has a **Card Layout** option: *Image Above Text* (the original look — image on top, title/text in a panel below) or *Image Fills Card (text centred)* — the image covers the whole card with the title, text and button centred on top of it.
  - **Per-card image overlay.** Every card now has an **Image Overlay Colour** (like the main hero) — a colour tint over the image that keeps centred text readable in the "Image Fills Card" layout. Leave blank for no overlay.
  - **Animated hero overlay (Start & Final).** The hero's single overlay setting has been replaced with a **Start Overlay (full screen)** and a **Final Overlay (shrunk into grid)**. The overlay fades smoothly between the two as the hero shrinks — so you can have **no overlay while a video plays full screen**, then have a dark overlay fade in as it settles into the grid.
  - **Fade in text & button on scroll.** A new toggle makes the hero heading, sub heading and button start hidden and fade in as the hero shrinks. Perfect with a background video: visitors watch it distraction-free until they start scrolling.

### 1.2.4
- **Fixed squashed Hero to Bento cards on mobile.** On phones the whole section was still locked to a fixed screen height with equal rows, so once it stacked into one column every card became a short letterbox and its title/text were clipped (and raising the Card Image Height just zoomed the image inside that fixed box). On mobile the widget now skips the pinned "shrink into grid" scroll effect and lays the section out as a natural vertical stack: the hero gets a proper height, and each card grows to fit a full-size image **plus** its title and text. The **Card Image Height** control now genuinely resizes the image box on mobile.

### 1.2.3
- **Added mobile styling controls to the Hero to Bento Scroll widget.** You can now set a **responsive Card Image Height** (with desktop/tablet/mobile breakpoints) to prevent card images from being squashed on smaller screens, plus a **responsive Card Text Padding** control to fine-tune spacing on mobile. The CSS also now applies a sensible default min-height (180px) to card images on phones if you haven't set a custom value.

### 1.2.2
- **Fixed: widgets below a Hero to Bento section animated on page load** instead of on scroll. The pinned hero adds extra scroll space (its "pin spacer") that pushes everything below it further down the page. That extra space was not being accounted for, so every widget underneath calculated its scroll position as if the hero took no room and fired far too early — by the time you scrolled down to them, the animations had already finished. The hero's ScrollTrigger now has a high `refreshPriority`, and positions are recalculated after page load and once images / web fonts finish loading, so each widget now animates exactly when it scrolls into view.
- **Added YouTube and Vimeo support to the Hero to Bento Scroll background video.** Previously the Video URL box only accepted a direct `.mp4` link, so pasting a YouTube link showed nothing. You can now paste a normal YouTube link (`youtube.com/watch?v=…`, `youtu.be/…`, Shorts or embed links), a Vimeo link, or a direct `.mp4` file — it autoplays muted, loops, and is scaled to cover the hero with no black bars.

### 1.2.1
- Fixed **Hero to Bento Scroll** not working on the live site: on the front end the widget was being initialised twice, creating a second scroll-lock (pin) on the same hero. The two stacked pins broke the scroll maths and made the page jump straight past the section. It now initialises once and scrolls smoothly.
- The hero heading / sub-heading / button are now **centred** and stay a readable size as the hero scales, instead of sliding off the left edge.
- The **GSAP Animations** category is now reliably forced to the **very top** of the Elementor widget panel, even when Elementor Pro and add-on packs (e.g. Ultimate Addons) are active.

### 1.2.0
- Added a new **Hero to Bento Scroll** widget — a pinned full-screen hero (video / image / colour) that shrinks into a bento grid of cards on scroll, inspired by elementor.com.
- **Animated Text** word / line / character reveals now have **Reveal Direction** (from below / above / left / right, scale, or fade-only) and **Reveal Order** (left-to-right, right-to-left, from the centre, from the edges, or random) controls.
- The **GSAP Animations** category now appears at the **top** of the Elementor widget panel so the widgets are easy to find.
- Plugin now ships **10 widgets** in total.

### 1.1.0
- Added 4 new widgets: **Animated Text**, **3D Icon Box**, **Reveal on Scroll** (text + images), and **SVG Animator** (free self-drawing lines).
- Plugin now ships **9 widgets** in total.

### 1.0.0
- Initial release with 5 widgets: Animated Heading, Scroll Counter, Parallax Section, Staggered Card Grid, Timeline Reveal.

---

## License
This plugin is licensed under the GPL-2.0+ license. GSAP is loaded from the cdnjs CDN and is subject to its own [standard license](https://gsap.com/community/standard-license/).
