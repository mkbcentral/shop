# Quick Start: Product Labels in Livewire

## 🚀 How to Use (For End Users)

### Step 1: Navigate to Products
- Click on **Produits** in the main menu
- You'll see the list of all your products

### Step 2: Select Products
- Check the boxes next to the products you want to print labels for
- You can select as many as you need (recommended: max 100 at once)
- Tip: Use filters to narrow down products first

### Step 3: Generate Labels
- Find the **Actions groupées** dropdown at the top of the list
- Select **"Générer Étiquettes"** from the dropdown
- Click the blue **"Appliquer"** button

### Step 4: Configure Your Labels
A popup window will appear with options:

**Format d'étiquette** (Label Size):
- 🏷️ **Petite** (80×50mm) - For small items
- 🏷️ **Moyenne** (100×70mm) - Standard size ⭐ Recommended
- 🏷️ **Grande** (A4) - Full page format

**Colonnes par page** (Columns):
- Choose from 1 to 4 columns
- Default: 2 columns ⭐ Recommended

**Options d'affichage** (Display Options):
- ✅ **Afficher le prix** - Show product price
- ✅ **Afficher le code-barres** - Show scannable barcode
- ✅ **Afficher le QR code** - Show QR code with product data

### Step 5: Generate and Download
- Click the green **"Générer"** button
- Your PDF will download automatically
- Open the PDF to preview your labels

### Step 6: Print
- Open the downloaded PDF
- Print using your label printer or regular printer
- Cut the labels if needed
- Apply to products

## 💡 Tips & Best Practices

### Choosing the Right Format

**Use Small (80×50mm) for:**
- Small retail items
- Price tags
- Shelf labels
- Items with limited space

**Use Medium (100×70mm) for:**
- Most products (best all-around choice)
- Products that need all information visible
- Standard label sheets

**Use Large (A4) for:**
- Demonstration or display purposes
- Large products
- When you need extra large text

### Column Selection Guide

**1 Column:**
- Large labels that need maximum space
- When printing on A4 paper
- Detailed product information needed

**2 Columns:** ⭐ **RECOMMENDED**
- Best for standard label sheets
- Good balance of size and paper usage
- Works well with most formats

**3 Columns:**
- Compact format
- Save paper
- Smaller labels

**4 Columns:**
- Maximum density
- Best for small format (80×50mm)
- Mass printing

### Common Use Cases

**Scenario 1: New Product Batch**
```
1. Filter by: Recently added products
2. Select: All new products
3. Format: Medium, 2 columns
4. Options: All enabled
5. Print and apply to inventory
```

**Scenario 2: Price Update**
```
1. Filter by: Category (e.g., "Electronics")
2. Select: Products with new prices
3. Format: Small, 3 columns
4. Options: Price + Barcode only
5. Print new price labels
```

**Scenario 3: Shelf Organization**
```
1. Filter by: Location or category
2. Select: All items for this shelf
3. Format: Medium, 2 columns
4. Options: All enabled
5. Print and organize shelves
```

## 🎨 Label Content

Each label includes (when enabled):

```
┌─────────────────────┐
│ PRODUCT NAME        │
│                     │
│ ▬▬▬▬▬▬▬▬▬▬▬        │ ← Barcode
│ 1234567890128       │
│                     │
│ Prix: 5,000 FC     │
│                     │
│ [QR]                │ ← QR Code
└─────────────────────┘
```

### What's in the QR Code?
Scan it with your phone to see:
- Product ID
- Product name
- SKU/Reference
- Price
- Barcode number

Perfect for:
- Quick product lookup on mobile
- Inventory management apps
- Customer information

## ⚠️ Troubleshooting

### PDF doesn't download
1. Check your browser's download settings
2. Allow pop-ups from this site
3. Try a different browser
4. Contact support if issue persists

### Labels look wrong when printed
1. Check your printer settings
2. Make sure "Actual size" is selected (not "Fit to page")
3. Print a test page first
4. Adjust format/columns if needed

### Barcode won't scan
1. Make sure barcode is enabled in options
2. Print at higher quality
3. Ensure good contrast (black on white)
4. Clean your scanner lens

### QR code won't scan
1. Make sure QR code is enabled
2. Try a different QR scanner app
3. Check your internet connection
4. Print at higher quality

### Too many labels on page
- Reduce the number of columns
- Select a larger format
- Print fewer products per sheet

### Labels too small
- Increase format size
- Reduce number of columns
- Use Large (A4) format

## 📱 Mobile Scanning

After printing labels:

**To scan barcodes:**
1. Use any barcode scanner app
2. Point at the barcode
3. Numbers appear automatically

**To scan QR codes:**
1. Open your phone camera
2. Point at the QR code
3. Tap the notification that appears
4. View product details

## 🖨️ Printer Recommendations

**Label Printers:**
- Zebra ZD420 (thermal transfer)
- Brother QL-800 (direct thermal)
- Dymo LabelWriter 450

**Regular Printers:**
- Any inkjet or laser printer works
- Use Avery label sheets for easy peeling
- Match format size to your label sheets

## 📊 Bulk Printing Strategy

For large inventories:

1. **Organize by category**
   - Print all electronics first
   - Then clothing, then food, etc.
   - Easier to apply systematically

2. **Print in batches**
   - Don't exceed 100 products at once
   - Prevents PDF timeout issues
   - Easier to manage

3. **Use consistent settings**
   - Same format for all in a category
   - Makes inventory look professional
   - Easier to read and scan

## ✨ Pro Tips

1. **Test first**: Always print 1-2 labels first to verify layout
2. **Save paper**: Use preview before printing full batch
3. **Organization**: Print by aisle/shelf for easy application
4. **Quality**: Use good quality paper for durability
5. **Backup**: Keep a digital copy of generated PDFs
6. **Regular updates**: Re-print when prices change

## 🆘 Need Help?

**Check these resources first:**
1. This quick start guide
2. `PRODUCT_LABELS_LIVEWIRE_GUIDE.md` (detailed guide)
3. `PRODUCT_LABELS_GUIDE.md` (technical reference)

**Still stuck?**
- Contact your system administrator
- Check the application logs
- Submit a support ticket

---

**Remember**: Always test with a few labels before printing hundreds! 🎯

**Happy Labeling!** 🏷️✨
