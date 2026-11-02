---
name: Athletic Pitch Precision
colors:
  surface: '#faf8ff'
  surface-dim: '#d2d9f4'
  surface-bright: '#faf8ff'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f2f3ff'
  surface-container: '#eaedff'
  surface-container-high: '#e2e7ff'
  surface-container-highest: '#dae2fd'
  on-surface: '#131b2e'
  on-surface-variant: '#3d4a42'
  inverse-surface: '#283044'
  inverse-on-surface: '#eef0ff'
  outline: '#6d7a72'
  outline-variant: '#bccac0'
  surface-tint: '#006c4a'
  primary: '#006948'
  on-primary: '#ffffff'
  primary-container: '#00855d'
  on-primary-container: '#f5fff7'
  inverse-primary: '#68dba9'
  secondary: '#2b6954'
  on-secondary: '#ffffff'
  secondary-container: '#adedd3'
  on-secondary-container: '#306d58'
  tertiary: '#a33900'
  on-tertiary: '#ffffff'
  tertiary-container: '#cc4900'
  on-tertiary-container: '#fffbff'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#85f8c4'
  primary-fixed-dim: '#68dba9'
  on-primary-fixed: '#002114'
  on-primary-fixed-variant: '#005137'
  secondary-fixed: '#b0f0d6'
  secondary-fixed-dim: '#95d3ba'
  on-secondary-fixed: '#002117'
  on-secondary-fixed-variant: '#0b513d'
  tertiary-fixed: '#ffdbce'
  tertiary-fixed-dim: '#ffb599'
  on-tertiary-fixed: '#370e00'
  on-tertiary-fixed-variant: '#7f2b00'
  background: '#faf8ff'
  on-background: '#131b2e'
  surface-variant: '#dae2fd'
  pitch-emerald: '#059669'
  pitch-light: '#10B981'
  pitch-deep: '#064E3B'
  pitch-surface: '#F0FDF4'
  action-amber: '#F59E0B'
  action-orange: '#EA580C'
  action-glow: '#FEF3C7'
  court-booked: '#94A3B8'
  court-booked-surface: '#F1F5F9'
  court-blocked: '#EF4444'
  court-blocked-surface: '#FEF2F2'
  canvas-bg: '#F8FAFC'
  surface-white: '#FFFFFF'
  arena-night: '#022C22'
typography:
  headline-xl:
    fontFamily: Space Grotesk
    fontSize: 48px
    fontWeight: '700'
    lineHeight: 56px
    letterSpacing: -0.03em
  headline-xl-mobile:
    fontFamily: Space Grotesk
    fontSize: 32px
    fontWeight: '700'
    lineHeight: 40px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Space Grotesk
    fontSize: 32px
    fontWeight: '700'
    lineHeight: 40px
    letterSpacing: -0.02em
  headline-lg-mobile:
    fontFamily: Space Grotesk
    fontSize: 24px
    fontWeight: '700'
    lineHeight: 32px
    letterSpacing: -0.01em
  headline-md:
    fontFamily: Space Grotesk
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
  headline-sm:
    fontFamily: Space Grotesk
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 28px
  body-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 18px
    fontWeight: '400'
    lineHeight: 28px
  body-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  body-sm:
    fontFamily: Plus Jakarta Sans
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  label-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 14px
    fontWeight: '700'
    lineHeight: 20px
    letterSpacing: 0.02em
  label-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 12px
    fontWeight: '600'
    lineHeight: 16px
    letterSpacing: 0.04em
  label-sm:
    fontFamily: Plus Jakarta Sans
    fontSize: 11px
    fontWeight: '600'
    lineHeight: 14px
    letterSpacing: 0.05em
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  gutter: 1.5rem
  gutter-mobile: 0.75rem
  margin: 2rem
  margin-mobile: 1rem
  space-2xs: 0.25rem
  space-xs: 0.5rem
  space-sm: 0.75rem
  space-md: 1rem
  space-lg: 1.5rem
  space-xl: 2rem
  space-2xl: 3rem
---

## Brand & Style

The design system establishes a high-performance, athletic aesthetic tailored for active sports venue management, court reservations, and player scheduling. The interface balances high-intensity sports energy with the clinical clarity of a modern booking engine.

The visual style blends **Modern Athletic Utility** with **Tonal Glass-Accented Minimalism**:
- **Tone & Atmosphere:** Confident, dynamic, organized, and responsive. Surfaces evoke well-maintained indoor facilities, pristine turf, and illuminated evening arenas.
- **Target Audience:** Grassroots club managers, recreational players, venue operators, and sports enthusiasts seeking instantaneous court booking with zero cognitive friction.
- **Visual Tenets:** Crisp border contours, bold typography, tactile micro-interactions, and unmistakable real-time status signals for court availability.

## Colors

The palette derives its core identity from athletic fields, court line markings, and high-visibility digital scoreboards.

### Role Assignment
- **Primary (`#059669` / `#10B981`):** Represents the pitch, open availability, vitality, and standard confirmation states. Used for primary branding, active sports tabs, and accessible slots.
- **Secondary (`#064E3B` / `#022C22`):** Grounded deep forest slate. Anchors dark structural containers, hero booking consoles, site footers, and contrasting summary cards.
- **Tertiary & CTA Accent (`#EA580C` / `#F59E0B`):** High-voltage sports orange/amber. Strictly reserved for high-intent actions—such as "Book Now", in-cart slot confirmations, and countdown timers.
- **Neutral (`#0F172A` / `#F8FAFC`):** Balanced cold deep slate for high-contrast typography, against pure white and off-white court schedule canvases.

### Slot Grid State Tokens
- **Available (`pitch-emerald` / `pitch-surface`):** Clear emerald border, soft green tint, indicating ready-to-book status.
- **Selected (`action-orange` / `action-glow`):** Saturated amber-orange frame with subtle ambient glow and inverted text, representing active reservation.
- **Booked / Occupied (`court-booked` / `court-booked-surface`):** Muted cool gray fill with strikethrough or faded typographic indicators. Non-interactive.
- **Blocked / Maintenance (`court-blocked` / `court-blocked-surface`):** Rose red border and tinted fill denoting closed or unavailable courts.

## Typography

The type scale combines the industrial, dynamic impact of **Space Grotesk** for display headlines and arena metrics with the exceptional legibility of **Plus Jakarta Sans** for multi-slot scheduling grids, pricing breakdowns, and metadata.

- **Headlines (`Space Grotesk`):** Tight tracking (`-0.02em` to `-0.03em`) and heavy weights produce an athletic, commanding hierarchy reminiscent of stadium stadium boards and score graphics.
- **Body & Labels (`Plus Jakarta Sans`):** Balanced aperture and clear numeric characters allow users to read dynamic court pricing (e.g., `Rp 150.000`), split hours, and slot increments without ambiguity.
- **Numbers & Metrics:** Schedule time units and court numbers lean on tabular alignment when set inside repetitive grid matrices.

## Layout & Spacing

The layout is built around a structured 12-column fluid grid on desktop, collapsing to 4 columns on mobile. It is optimized for multi-court timetable scanning and touch interactions.

- **Breakpoints:**
  - **Mobile (`<640px`):** Single column stacked layout. Slots are rendered in dense 3-to-4 column touch matrices. Gutter: `0.75rem`, Screen Margin: `1rem`. Minimum touch target is enforced at `44px × 44px`.
  - **Tablet (`640px - 1024px`):** 6-column grid with sticky booking sub-summaries. Gutter: `1rem`, Margin: `1.5rem`.
  - **Desktop (`>1024px`):** 12-column grid. Court details and calendar filters occupy 8 columns, while the sticky booking tray and real-time total calculator occupy the remaining 4 columns. Gutter: `1.5rem`, Margin: `2rem`.
- **Rhythm:** Spacing follows a strict `4px / 8px` base cadence. Compact tokens (`space-2xs` to `space-sm`) structure slot card internals, while generous tokens (`space-lg` to `space-2xl`) frame major court sections.

## Elevation & Depth

Visual hierarchy leverages crisp structural borders reinforced with soft ambient glows, avoiding dense, muddy drop shadows.

- **Level 0 (Flat Canvas):** Neutral ground (`#F8FAFC`) with no shadow. Structural dividers rely on `1px solid #E2E8F0`.
- **Level 1 (Surface Cards & Venue Tiles):** Background white (`#FFFFFF`) with a hairline border (`1px solid #E2E8F0`) and an ambient, low-opacity drop shadow: `0 1px 3px rgba(15, 23, 42, 0.05), 0 4px 6px -1px rgba(15, 23, 42, 0.02)`.
- **Level 2 (Active Slots & Hover States):** Lifted surface with a targeted chromatic glow:
  - *Available hover:* `0 4px 12px rgba(5, 150, 105, 0.12)`.
  - *Selected slot:* `0 0 0 2px #EA580C, 0 4px 14px rgba(234, 88, 12, 0.25)`.
- **Level 3 (Sticky Booking Trays & Floating Bars):** Positioned along the viewport base. Utilizes subtle dark elevation: `0 -4px 20px rgba(6, 78, 59, 0.12)` accompanied by high-density frosted backdrops (`backdrop-blur-md`).
- **Dark Mode / Night Arena Containers:** Hero reservation shells use deep emerald slate (`#064E3B`) with ambient inner shadows and crisp contrasting light badges.

## Shapes

The system uses `roundedness: 2` (base radius `0.5rem` / `8px`) to achieve an engineered, modern athletic geometry.

- **Base Components (`0.5rem` / `8px`):** Booking time slots, form inputs, counter buttons, and facility pill badges.
- **Containers & Venue Cards (`1rem` / `16px`):** Large court cards, modal reservation panels, and calendar day switchers.
- **Pill Badges (`9999px`):** Status flags (e.g., "Full Court", "Vinyl Carpet", "LED Lighting"), sport category filter tabs, and real-time availability counts.

## Components

### Buttons
- **Primary Action (CTA):** Background `action-orange` (`#EA580C`) hovering to `#C2410C`. Crisp white text (`#FFFFFF`), `font-weight: 700`, `min-height: 48px`, border radius `0.5rem`. Used for "Booking Sekarang", "Lanjut ke Pembayaran", and instant checkout.
- **Pitch Interactive:** Background `pitch-emerald` (`#059669`) hovering to `pitch-deep` (`#064E3B`). White text, used for slot confirmation, daytime scheduling, and venue selection.
- **Secondary / Outline:** Transparent fill, `1.5px solid #CBD5E1`, text `#0F172A`. Hover transitions to background `#F1F5F9`.
- **Touch Target Rule:** All buttons maintain a minimum boundary of `44px` on mobile screens.

### Slot Grid & Time Blocks
- **Component Anatomy:** Minimum `44px` height and flexible width. Top line shows slot time (e.g., `08.00`); bottom line displays price in smaller, high-contrast text (e.g., `Rp 150.000`).
- **Available State:** Off-white/emerald tint background (`#F0FDF4`), border `1px solid #A7F3D0`, text `#064E3B`.
- **Selected State:** Vibrant amber fill (`#FEF3C7`), border `2px solid #EA580C`, text `#9A3412`, elevated with orange ambient focus ring.
- **Booked State:** Disabled background (`#F1F5F9`), border `1px solid #E2E8F0`, muted gray text (`#94A3B8`), cursor `not-allowed`. Strikethrough pricing.
- **Maintenance / Blocked:** Subdued red surface (`#FEF2F2`), red border (`#FECACA`), label "Tutup" or "Maintenance".

### Venue Cards (`<x-venue-card />`)
- **Structure:** Aspect ratio `16:9` court thumbnail image overlayed with dynamic sports pills (e.g., "Futsal", "Badminton", "Mini Soccer") in the top left.
- **Content:** Headline display of court name, surface type specification (e.g., `Karpet Vinyl`, `Rumput Sintetis`), pricing matrix (day vs. peak night pricing), and direct slot inspection link.
- **Border:** `1px solid #E2E8F0`, transitioning on hover to `1.5px solid #10B981` with slight image zoom (`scale: 1.02`).

### Chips & Filter Pills
- **Category Filters:** Pill-shaped tags (`rounded-full`) with `padding: 0.5rem 1rem`. Active sport tab has a solid `#064E3B` fill with white text; inactive tabs use white surfaces with `#E2E8F0` borders.

### Booking Summary Bar (`<booking-summary />`)
- **Desktop:** Sticky right-column card. Displays picked court count, calculated aggregate hours, subtotal, and dynamic lock countdown timer (e.g., "Slot dikunci selama 09:59").
- **Mobile:** Sticky bottom bar (`bottom-0`, fixed). Displays picked hours tally, total amount, and high-contrast `#EA580C` checkout button spanning half viewport width.

### Status Badges & Alerts
- **Badge Types:** Compact capsule shape (`rounded-full`) with an internal `6px` status dot. Emerald for "Tersedia", Amber for "Terpilih", Slate for "Terisi", and Rose for "Maintenance".
- **Reservation Lock Notice:** Floating amber notification banner warning players of pessimistic lock expiration to prevent double-booking.