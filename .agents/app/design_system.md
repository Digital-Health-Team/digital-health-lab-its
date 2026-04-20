# Design System Brief of Project

**Target Audience:** UI/UX Designer, Frontend Developer
**Design Theme:** Clean, Modern, Trustworthy, Futuristic, Transaction-Ready
**Styling Framework:** Tailwind CSS + Hero UI

## 1. Overview

The interface design is focused on clarity (clutter-free) to highlight innovation works and 3D images. The aesthetics combine the institution's academic authority and premium e-commerce functionality.

## 2. Design Principles

- **Content-First Visibility:** Neutral backgrounds ensure 3D models and work thumbnails do not overlap with decorative elements.
- **Interactive Modularity:** Utilizes a MakerWorld/Pinterest-style Masonry Grid for the catalog. The 3D Viewer box has elevation and independent controls from the main page scroll.
- **Contextual Badging:** Consistent use of color badges across all dashboards (Green for Verified, Yellow for Pending/Slicing, Red for Rejected).

## 3. Color Palette

| Category         | Hex Code                 | Primary UI Usage                                                                                               |
| :--------------- | :----------------------- | :------------------------------------------------------------------------------------------------------------- |
| Primary (Brand)  | `#00426D` (Deep Blue)    | Header, Main Navigation, Primary Action Buttons (Login, Order). Gives an academic and professional impression. |
| Secondary (Tech) | `#00A8B5` (Medical Teal) | Hover states, interactive elements, 3D Viewer UI, Order Progress Bar.                                          |
| Accent           | `#FFC72C` (ITS Yellow)   | "Pending" status badges, stock warnings, secondary Call to Action.                                             |
| Background       | `#F8F9FA` (Off-White)    | Dashboard and main page backgrounds for maximum contrast.                                                      |
| Surface/Card     | `#FFFFFF` (Pure White)   | Product cards, multi-step upload form containers.                                                              |
| Text Primary     | `#1E293B` (Dark Slate)   | Heading texts, work description paragraphs, table data text.                                                   |

## 4. Typography

- **Heading & Titles:** Plus Jakarta Sans or Poppins (Modern, friendly, and geometric).
- **Body Text & Transaction Data:** Inter or Roboto (High readability for admin operational tables and 3D technical specifications).

## 5. Core UI Components

- **Interactive 3D Canvas:** A container with a loading spinner or asynchronous percentage. Cursor rotation controls must be isolated so they don't trigger Inertia page transitions.
- **Masonry Cards:** Flexible components wrapping dynamic images and creator info in the card footer.
- **Dropzone Area:** A file upload component with a Teal dashed border and state transitions when files are dragged into the area.
