# Master Poplampo's Furniture Shop Website

**ALWAYS follow these instructions first** and only fallback to search or bash commands when you encounter unexpected information that does not match the info here.

## Project Overview
Master Poplampo's Furniture Shop is a static HTML/CSS website showcasing a carpentry and furniture business based in Tema, Michel Camp, Ghana. The site features a main page with business information and a comprehensive gallery of custom furniture work including beds, kitchen cabinets, shelves, TV stands, and wardrobes.

## Working Effectively

### Quick Start - REQUIRED for all changes
1. **Start HTTP server**: `cd /path/to/repo && python3 -m http.server 8080` - takes < 5 seconds
2. **Validate HTML**: `htmlhint index.html Gallery/Gallery.html` - takes < 10 seconds 
3. **Test in browser**: Navigate to `http://localhost:8080` - NEVER CANCEL server, let it run
4. **Manual testing**: ALWAYS test navigation, phone links, and gallery functionality

### Build and Test Commands
- **NO BUILD REQUIRED**: This is a static HTML/CSS website with no build system or dependencies
- **Serve website**: `python3 -m http.server 8080` (from repo root)
- **HTML validation**: `htmlhint *.html Gallery/*.html Gallery/Gallery_links/*.html`
- **Manual testing time**: 2-3 minutes for complete user scenarios

### Installation Requirements
- **Install HTML validator**: `npm install -g htmlhint` (one-time setup, takes ~30 seconds)
- **No other dependencies**: Pure HTML/CSS project, no package.json or build tools needed

## Validation Scenarios - MANDATORY

**ALWAYS run these validation steps after making ANY changes:**

### 1. Server Startup Test
```bash
cd /path/to/repo
python3 -m http.server 8080
# Server starts in < 5 seconds, NEVER CANCEL
# Should see: "Serving HTTP on 0.0.0.0 port 8080"
```

### 2. HTML Validation Test
```bash
htmlhint index.html
htmlhint Gallery/Gallery.html
# Should complete in < 10 seconds
# Expect some existing validation issues (do not fix unless required)
```

### 3. Manual Navigation Test - CRITICAL
1. **Main page test**: Navigate to `http://localhost:8080`
   - Verify page loads with Master Poplampo branding
   - Test phone number links: `(233) 273815943` and `(233) 256756212`
   - Should trigger tel: protocol in browser
2. **Gallery navigation test**: Click "Gallery" link in navigation
   - Should navigate to `Gallery/Gallery.html`
   - Verify 6 category links appear: Bed, Furniture, Kitchen Cabinets, Shelves, TV Stands, Wardrobes
3. **Sub-gallery test**: Click any category (e.g., "Kitchen Cabinets")
   - Should navigate to individual gallery page
   - Click "Go to Homepage" - should return to main page
   - Click "Back to Gallery Home" - should return to gallery

### 4. Responsive Design Test
- **Desktop view**: Default browser size - navigation should be horizontal
- **Mobile simulation**: Narrow browser window - navigation should stack vertically

## File Structure and Key Locations

```
Repository Root
├── index.html                 # Main business page (MOST IMPORTANT)
├── css/stylesheet.css         # Main CSS file with mobile responsive rules
├── images/                    # Main page images (backgrounds, photos)
├── Gallery/
│   ├── Gallery.html          # Gallery main page
│   ├── css/Gallery.css       # Gallery-specific CSS
│   ├── images/               # Gallery category images organized by type
│   └── Gallery_links/        # Individual category pages (Bed.html, etc.)
├── Gallery_links/            # Category preview images (jpg files)
└── README.md                 # Basic project description
```

### Important Files to Check When Making Changes
- **index.html**: Main page - contains all business content and navigation
- **css/stylesheet.css**: Contains mobile responsive CSS (@media queries at bottom)
- **Gallery/Gallery.html**: Gallery main page with category links
- **Gallery/css/Gallery.css**: Gallery styling (separate from main CSS)

## Common Issues and Troubleshooting

### Known HTML Validation Issues (DO NOT FIX unless specifically requested)
- Single quotes in href attributes (should be double quotes)
- Unescaped `>` characters in text
- Some malformed tags (e.g., `</No>`)
- Typos in meta viewport attributes in gallery pages

### Navigation Issues
- **Gallery images not showing**: Check file paths in Gallery/Gallery_links/*.html
- **Broken internal links**: Verify relative paths are correct (../ for parent directories)
- **CSS not loading**: Check that CSS file paths are correct in HTML head sections

### Performance Notes
- **Large image files**: Gallery images are high-resolution (300KB - 1.4MB each)
- **No image optimization**: Images served at full resolution
- **No caching**: Static server has basic caching only

## Development Workflow

### For Content Changes
1. **Edit HTML files directly** (no build step required)
2. **Refresh browser** to see changes immediately
3. **Run HTML validation** to check for errors
4. **Test navigation** to ensure links still work

### For Style Changes
1. **Edit CSS files** (`css/stylesheet.css` or `Gallery/css/Gallery.css`)
2. **Hard refresh browser** (Ctrl+F5) to clear CSS cache
3. **Test responsive design** by resizing browser window
4. **Check mobile styles** using browser dev tools

### Before Committing Changes
- **ALWAYS validate HTML**: `htmlhint *.html Gallery/*.html`
- **ALWAYS test full navigation flow**: Main → Gallery → Sub-page → Back
- **ALWAYS test phone links**: Click both phone numbers to verify tel: protocol
- **Check responsive layout**: Test both desktop and mobile views

## Technical Specifications

- **Language**: Pure HTML5 and CSS3
- **Framework**: None (vanilla HTML/CSS)
- **Server**: Any HTTP server (Python SimpleHTTP recommended for development)
- **Browser Compatibility**: Modern browsers (ES5+ assumed)
- **Mobile Support**: Responsive CSS with @media queries for screens < 480px width
- **Contact Integration**: Phone links use tel: protocol for mobile dialing

## Time Estimates for Common Tasks

- **Start development server**: < 5 seconds
- **HTML validation (all pages)**: < 15 seconds  
- **Complete manual testing**: 2-3 minutes
- **Server shutdown**: Instant (Ctrl+C)
- **Content changes**: Immediate (no build step)
- **CSS changes**: < 5 seconds with hard refresh

**REMEMBER**: This is a static website - NO build process, NO dependency management, NO compilation required. Changes are visible immediately after browser refresh.