import java.awt.*;
import java.awt.image.BufferedImage;
import java.io.File;
import javax.imageio.ImageIO;

public class DiagramGenerator {

    public static void main(String[] args) {
        try {
            File assetsDir = new File("../report_gen/assets");
            if (!assetsDir.exists()) {
                assetsDir.mkdirs();
            }

            generateFig1_1();
            generateFig3_1();
            generateFig4_1();
            generateFig4_2();
            generateFig4_3();
            generateFig4_4();
            generateFig4_5();
            generateFig5_1();

            System.out.println("Success: All 8 diagrams generated successfully in assets/ directory.");
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private static Graphics2D createGraphics(BufferedImage img) {
        Graphics2D g2d = img.createGraphics();
        g2d.setRenderingHint(RenderingHints.KEY_ANTIALIASING, RenderingHints.VALUE_ANTIALIAS_ON);
        g2d.setRenderingHint(RenderingHints.KEY_TEXT_ANTIALIASING, RenderingHints.VALUE_TEXT_ANTIALIAS_ON);
        g2d.setColor(Color.WHITE);
        g2d.fillRect(0, 0, img.getWidth(), img.getHeight());
        return g2d;
    }

    private static void drawCenteredString(Graphics2D g2d, String text, int x, int y, int width, int height) {
        FontMetrics metrics = g2d.getFontMetrics(g2d.getFont());
        int stringWidth = metrics.stringWidth(text);
        int stringHeight = metrics.getAscent();
        g2d.drawString(text, x + (width - stringWidth) / 2, y + (height - stringHeight) / 2 + stringHeight);
    }

    private static void drawArrow(Graphics2D g2d, int x1, int y1, int x2, int y2) {
        g2d.drawLine(x1, y1, x2, y2);
        double angle = Math.atan2(y2 - y1, x2 - x1);
        int arrowSize = 8;
        int dx1 = (int) (x2 - arrowSize * Math.cos(angle - Math.PI / 6));
        int dy1 = (int) (y2 - arrowSize * Math.sin(angle - Math.PI / 6));
        int dx2 = (int) (x2 - arrowSize * Math.cos(angle + Math.PI / 6));
        int dy2 = (int) (y2 - arrowSize * Math.sin(angle + Math.PI / 6));
        g2d.fillPolygon(new int[]{x2, dx1, dx2}, new int[]{y2, dy1, dy2}, 3);
    }

    private static void drawRoundBox(Graphics2D g2d, String title, int x, int y, int w, int h) {
        g2d.setColor(new Color(245, 245, 245));
        g2d.fillRoundRect(x, y, w, h, 10, 10);
        g2d.setColor(Color.BLACK);
        g2d.setStroke(new BasicStroke(2));
        g2d.drawRoundRect(x, y, w, h, 10, 10);
        g2d.setFont(new Font("Arial", Font.BOLD, 12));
        drawCenteredString(g2d, title, x, y, w, h);
    }

    // 1. Roadmap of Report Chapters
    private static void generateFig1_1() throws Exception {
        int w = 800, h = 600;
        BufferedImage img = new BufferedImage(w, h, BufferedImage.TYPE_INT_ARGB);
        Graphics2D g = createGraphics(img);

        String[] chapters = {
            "Chapter 1: Introduction",
            "Chapter 2: Literature Review",
            "Chapter 3: System Analysis",
            "Chapter 4: System Design",
            "Chapter 5: System Implementation",
            "Chapter 6: Testing & Evaluation",
            "Chapter 7: Conclusion & Further Work"
        };

        int boxW = 350, boxH = 45;
        int x = (w - boxW) / 2;
        int startY = 30;
        int gap = 80;

        for (int i = 0; i < chapters.length; i++) {
            int y = startY + i * gap;
            drawRoundBox(g, chapters[i], x, y, boxW, boxH);
            if (i < chapters.length - 1) {
                g.setColor(Color.BLACK);
                g.setStroke(new BasicStroke(2));
                drawArrow(g, x + boxW / 2, y + boxH, x + boxW / 2, y + gap);
            }
        }

        ImageIO.write(img, "png", new File("../report_gen/assets/fig1_1.png"));
    }

    // 2. Hardware Deployment Configuration
    private static void generateFig3_1() throws Exception {
        int w = 800, h = 400;
        BufferedImage img = new BufferedImage(w, h, BufferedImage.TYPE_INT_ARGB);
        Graphics2D g = createGraphics(img);

        int boxW = 200, boxH = 60;
        int startY = 170;

        // Draw entities
        drawRoundBox(g, "Client Device (Browser/Mobile)", 40, startY, boxW, boxH);
        drawRoundBox(g, "Apache Web Server (PHP 8.1)", 300, startY, boxW, boxH);
        drawRoundBox(g, "MySQL Database Server", 560, startY, boxW, boxH);

        // Arrows & labels
        g.setColor(Color.BLACK);
        g.setStroke(new BasicStroke(2));
        g.setFont(new Font("Arial", Font.PLAIN, 10));

        // Client <-> Server
        drawArrow(g, 240, startY + 20, 300, startY + 20);
        g.drawString("HTTP GET/POST Requests", 245, startY + 12);
        
        drawArrow(g, 300, startY + 40, 240, startY + 40);
        g.drawString("HTML/CSS/JS Responses", 245, startY + 52);

        // Server <-> Database
        drawArrow(g, 500, startY + 20, 560, startY + 20);
        g.drawString("SQL Queries (PDO)", 505, startY + 12);

        drawArrow(g, 560, startY + 40, 500, startY + 40);
        g.drawString("Result Sets / Records", 505, startY + 52);

        // LAN outline
        g.setColor(Color.DARK_GRAY);
        g.setStroke(new BasicStroke(1, BasicStroke.CAP_BUTT, BasicStroke.JOIN_MITER, 10, new float[]{5}, 0));
        g.drawRect(280, 100, 500, 200);
        g.setFont(new Font("Arial", Font.BOLD, 11));
        g.drawString("Local Host Server Environment (XAMPP)", 290, 120);

        ImageIO.write(img, "png", new File("../report_gen/assets/fig3_1.png"));
    }

    // 3. Modular Architecture
    private static void generateFig4_1() throws Exception {
        int w = 800, h = 500;
        BufferedImage img = new BufferedImage(w, h, BufferedImage.TYPE_INT_ARGB);
        Graphics2D g = createGraphics(img);

        drawRoundBox(g, "FlavorHub Online Food Ordering System", 250, 40, 300, 50);

        // Split to Customer and Admin
        drawRoundBox(g, "Customer Module", 100, 150, 250, 45);
        drawRoundBox(g, "Administrator Module", 450, 150, 250, 45);

        // Lines to split
        g.setColor(Color.BLACK);
        g.setStroke(new BasicStroke(2));
        g.drawLine(400, 90, 400, 120);
        g.drawLine(225, 120, 575, 120);
        drawArrow(g, 225, 120, 225, 150);
        drawArrow(g, 575, 120, 575, 150);

        // Sub modules customer
        drawRoundBox(g, "User Registration & Login", 100, 230, 250, 40);
        drawRoundBox(g, "Menu Browsing & Search", 100, 290, 250, 40);
        drawRoundBox(g, "Shopping Cart & Checkout", 100, 350, 250, 40);
        drawRoundBox(g, "Real-Time Order Tracking", 100, 410, 250, 40);

        // Connect customer sub modules
        g.drawLine(225, 195, 225, 230);
        g.drawLine(225, 270, 225, 290);
        g.drawLine(225, 330, 225, 330); // spacer
        g.drawLine(225, 330, 225, 350);
        g.drawLine(225, 390, 225, 410);

        // Sub modules admin
        drawRoundBox(g, "Food & Category Management", 450, 230, 250, 40);
        drawRoundBox(g, "Order Management & Tracking", 450, 290, 250, 40);
        drawRoundBox(g, "Sales & Income Reporting", 450, 350, 250, 40);
        drawRoundBox(g, "Secure Access Control", 450, 410, 250, 40);

        // Connect admin sub modules
        g.drawLine(575, 195, 575, 230);
        g.drawLine(575, 270, 575, 290);
        g.drawLine(575, 330, 575, 350);
        g.drawLine(575, 390, 575, 410);

        ImageIO.write(img, "png", new File("../report_gen/assets/fig4_1.png"));
    }

    // 4. Top-Level System Architecture and Component Routing
    private static void generateFig4_2() throws Exception {
        int w = 800, h = 600;
        BufferedImage img = new BufferedImage(w, h, BufferedImage.TYPE_INT_ARGB);
        Graphics2D g = createGraphics(img);

        // Draw Layer Boxes
        g.setFont(new Font("Arial", Font.BOLD, 12));
        
        // 1. Presentation Layer (UI)
        g.setColor(new Color(245, 245, 245));
        g.fillRect(100, 50, 600, 80);
        g.setColor(Color.BLACK);
        g.drawRect(100, 50, 600, 80);
        g.drawString("PRESENTATION LAYER (Client Browser UI)", 120, 70);
        drawRoundBox(g, "HTML5 / Bootstrap CSS", 150, 80, 180, 35);
        drawRoundBox(g, "JavaScript (AJAX Fetch)", 470, 80, 180, 35);

        // 2. Controller / API Routing Layer
        g.setColor(new Color(245, 245, 245));
        g.fillRect(100, 180, 600, 80);
        g.setColor(Color.BLACK);
        g.drawRect(100, 180, 600, 80);
        g.drawString("CONTROLLER / ROUTING LAYER (PHP REST API)", 120, 200);
        drawRoundBox(g, "api/place_order.php", 150, 215, 180, 35);
        drawRoundBox(g, "admin/orders_handler.php", 470, 215, 180, 35);

        // 3. Service Layer (Business Logic)
        g.setColor(new Color(245, 245, 245));
        g.fillRect(100, 310, 600, 80);
        g.setColor(Color.BLACK);
        g.drawRect(100, 310, 600, 80);
        g.drawString("SERVICE LAYER (Business Logic & Validation)", 120, 330);
        drawRoundBox(g, "OrderService.php", 300, 345, 200, 35);

        // 4. Data Access Layer
        g.setColor(new Color(245, 245, 245));
        g.fillRect(100, 440, 600, 80);
        g.setColor(Color.BLACK);
        g.drawRect(100, 440, 600, 80);
        g.drawString("DATA ACCESS LAYER (DAO & Database)", 120, 460);
        drawRoundBox(g, "OrderDAO.php", 150, 475, 150, 35);
        drawRoundBox(g, "Database.php (PDO)", 330, 475, 150, 35);
        drawRoundBox(g, "MySQL Database", 520, 475, 150, 35);

        // Connecting Arrows between layers
        g.setStroke(new BasicStroke(2));
        
        // Presentation -> Routing
        drawArrow(g, 240, 130, 240, 180);
        drawArrow(g, 560, 180, 560, 130);
        g.setFont(new Font("Arial", Font.PLAIN, 10));
        g.drawString("JSON Requests", 160, 155);
        g.drawString("JSON Responses", 570, 155);

        // Routing -> Service
        drawArrow(g, 240, 260, 350, 310);
        drawArrow(g, 560, 260, 450, 310);

        // Service -> DAO
        drawArrow(g, 400, 390, 225, 440);
        drawArrow(g, 225, 440, 400, 390); // bi-directional

        // DAO <-> Connection <-> MySQL
        drawArrow(g, 300, 492, 330, 492);
        drawArrow(g, 480, 492, 520, 492);

        ImageIO.write(img, "png", new File("../report_gen/assets/fig4_2.png"));
    }

    // 5. Entity Relationship Diagram (ERD)
    private static void generateFig4_3() throws Exception {
        int w = 950, h = 700;
        BufferedImage img = new BufferedImage(w, h, BufferedImage.TYPE_INT_ARGB);
        Graphics2D g = createGraphics(img);

        g.setStroke(new BasicStroke(2));

        // Draw Table Boxes (Entity boxes with Attributes)
        drawTableBox(g, "users", new String[]{
            "id [PK]",
            "fullname",
            "email (Unique)",
            "password",
            "phone",
            "address",
            "created_at"
        }, 50, 50, 180, 150);

        drawTableBox(g, "orders", new String[]{
            "id [PK]",
            "order_id (Unique)",
            "user_id [FK]",
            "customer_name",
            "customer_phone",
            "customer_address",
            "payment_method",
            "special_instructions",
            "subtotal",
            "tax",
            "delivery_fee",
            "total",
            "status",
            "created_at"
        }, 360, 50, 220, 270);

        drawTableBox(g, "order_items", new String[]{
            "id [PK]",
            "order_id [FK]",
            "food_id [FK]",
            "price",
            "quantity"
        }, 700, 150, 180, 120);

        drawTableBox(g, "foods", new String[]{
            "id [PK]",
            "name",
            "category_name [FK]",
            "description",
            "ingredients",
            "price",
            "rating",
            "reviews_count",
            "image_url"
        }, 700, 380, 180, 180);

        drawTableBox(g, "categories", new String[]{
            "id [PK]",
            "name",
            "description"
        }, 380, 480, 180, 90);

        drawTableBox(g, "comments", new String[]{
            "id [PK]",
            "recipe_id [FK]",
            "user_id [FK]",
            "fullname",
            "email",
            "comment_text",
            "status",
            "created_at"
        }, 50, 380, 180, 170);

        // Drawing Relationship Lines (1 to Many)
        g.setColor(Color.BLACK);
        g.setStroke(new BasicStroke(1.5f));

        // users (1) <---> (0..*) orders
        g.drawLine(230, 100, 360, 100);
        drawCrowFoot(g, 360, 100, true);
        g.setFont(new Font("Arial", Font.PLAIN, 10));
        g.drawString("1", 240, 95);
        g.drawString("N", 345, 95);

        // orders (1) <---> (1..*) order_items
        g.drawLine(580, 200, 700, 200);
        drawCrowFoot(g, 700, 200, true);
        g.drawString("1", 590, 195);
        g.drawString("N", 685, 195);

        // foods (1) <---> (0..*) order_items
        g.drawLine(790, 380, 790, 270);
        drawCrowFoot(g, 790, 270, false);
        g.drawString("1", 795, 370);
        g.drawString("N", 795, 290);

        // categories (1) <---> (0..*) foods
        g.drawLine(560, 500, 700, 500);
        drawCrowFoot(g, 700, 500, true);
        g.drawString("1", 570, 495);
        g.drawString("N", 685, 495);

        // users (1) <---> (0..*) comments
        g.drawLine(140, 200, 140, 380);
        drawCrowFoot(g, 140, 380, false);
        g.drawString("1", 145, 220);
        g.drawString("N", 145, 360);

        ImageIO.write(img, "png", new File("../report_gen/assets/fig4_3.png"));
    }

    private static void drawTableBox(Graphics2D g2d, String tableName, String[] columns, int x, int y, int w, int h) {
        g2d.setColor(new Color(245, 245, 245));
        g2d.fillRect(x, y, w, h);
        g2d.setColor(Color.BLACK);
        g2d.drawRect(x, y, w, h);
        
        // Header
        g2d.setColor(new Color(220, 220, 220));
        g2d.fillRect(x, y, w, 25);
        g2d.setColor(Color.BLACK);
        g2d.drawRect(x, y, w, 25);
        
        g2d.setFont(new Font("Arial", Font.BOLD, 12));
        g2d.drawString(tableName, x + 10, y + 17);
        
        g2d.setFont(new Font("Arial", Font.PLAIN, 10));
        int textY = y + 42;
        for (String col : columns) {
            g2d.drawString("- " + col, x + 10, textY);
            textY += 16;
        }
    }

    private static void drawCrowFoot(Graphics2D g2d, int x, int y, boolean horizontal) {
        if (horizontal) {
            g2d.drawLine(x, y, x - 10, y - 5);
            g2d.drawLine(x, y, x - 10, y + 5);
        } else {
            g2d.drawLine(x, y, x - 5, y + 10);
            g2d.drawLine(x, y, x + 5, y + 10);
        }
    }

    // 6. Context Data Flow Diagram (Level 0 DFD)
    private static void generateFig4_4() throws Exception {
        int w = 800, h = 500;
        BufferedImage img = new BufferedImage(w, h, BufferedImage.TYPE_INT_ARGB);
        Graphics2D g = createGraphics(img);

        // Process (Circle)
        g.setColor(new Color(245, 245, 245));
        g.fillOval(300, 150, 200, 200);
        g.setColor(Color.BLACK);
        g.setStroke(new BasicStroke(2));
        g.drawOval(300, 150, 200, 200);
        
        g.setFont(new Font("Arial", Font.BOLD, 14));
        drawCenteredString(g, "0.0 FlavorHub", 300, 210, 200, 30);
        drawCenteredString(g, "Online Ordering", 300, 235, 200, 30);
        drawCenteredString(g, "System", 300, 260, 200, 30);

        // External Entities (Rectangles)
        drawRoundBox(g, "Customer", 40, 220, 140, 60);
        drawRoundBox(g, "Administrator", 620, 220, 140, 60);

        // Arrows & labels Customer -> Process
        g.setStroke(new BasicStroke(1.5f));
        g.setFont(new Font("Arial", Font.PLAIN, 10));

        // Customer -> System
        drawArrow(g, 180, 235, 300, 235);
        g.drawString("Register / Order Details", 185, 228);
        
        drawArrow(g, 300, 265, 180, 265);
        g.drawString("Menu List / Tracking ID", 185, 280);

        // Admin -> System
        drawArrow(g, 620, 235, 500, 235);
        g.drawString("Update Order / Catalog", 508, 228);

        drawArrow(g, 500, 265, 620, 265);
        g.drawString("Order Details / Reports", 508, 280);

        ImageIO.write(img, "png", new File("../report_gen/assets/fig4_4.png"));
    }

    // 7. Use Case Diagram
    private static void generateFig4_5() throws Exception {
        int w = 800, h = 700;
        BufferedImage img = new BufferedImage(w, h, BufferedImage.TYPE_INT_ARGB);
        Graphics2D g = createGraphics(img);

        // System boundary
        g.setColor(Color.BLACK);
        g.setStroke(new BasicStroke(1.5f));
        g.drawRect(200, 40, 400, 620);
        g.setFont(new Font("Arial", Font.BOLD, 12));
        g.drawString("FlavorHub Web App System", 210, 60);

        // Actors
        drawActor(g, "Customer", 80, 300);
        drawActor(g, "Administrator", 720, 300);

        // Use cases (Ovals)
        String[] cases = {
            "Register & Log In",
            "Browse Menu & Search",
            "Manage Shopping Cart",
            "Place Checkout Order",
            "Track Order Status",
            "Manage Food Catalog",
            "Process Order Status",
            "View Sales Reports"
        };

        int ovalW = 220, ovalH = 45;
        int startY = 80;
        int gap = 70;

        for (int i = 0; i < cases.length; i++) {
            int y = startY + i * gap;
            g.setColor(new Color(245, 245, 245));
            g.fillOval(290, y, ovalW, ovalH);
            g.setColor(Color.BLACK);
            g.drawOval(290, y, ovalW, ovalH);
            g.setFont(new Font("Arial", Font.PLAIN, 11));
            drawCenteredString(g, cases[i], 290, y, ovalW, ovalH);

            // Connect Actors
            if (i <= 4) {
                // Customer -> Use case
                g.drawLine(110, 320, 290, y + ovalH / 2);
            }
            if (i == 0 || i >= 5) {
                // Admin -> Use case
                g.drawLine(690, 320, 510, y + ovalH / 2);
            }
        }

        ImageIO.write(img, "png", new File("../report_gen/assets/fig4_5.png"));
    }

    private static void drawActor(Graphics2D g2d, String label, int x, int y) {
        g2d.setColor(Color.BLACK);
        g2d.setStroke(new BasicStroke(2));
        // Head
        g2d.drawOval(x - 15, y - 40, 30, 30);
        // Body
        g2d.drawLine(x, y - 10, x, y + 25);
        // Arms
        g2d.drawLine(x - 25, y, x + 25, y);
        // Legs
        g2d.drawLine(x, y + 25, x - 15, y + 55);
        g2d.drawLine(x, y + 25, x + 15, y + 55);
        
        g2d.setFont(new Font("Arial", Font.BOLD, 12));
        FontMetrics metrics = g2d.getFontMetrics(g2d.getFont());
        g2d.drawString(label, x - metrics.stringWidth(label) / 2, y + 75);
    }

    // 8. MVC Flow Diagram
    private static void generateFig5_1() throws Exception {
        int w = 800, h = 600;
        BufferedImage img = new BufferedImage(w, h, BufferedImage.TYPE_INT_ARGB);
        Graphics2D g = createGraphics(img);

        // Sequence Lifelines (Headers)
        g.setFont(new Font("Arial", Font.BOLD, 12));
        drawRoundBox(g, "Customer (Browser UI)", 40, 40, 150, 40);
        drawRoundBox(g, "Controller (API Route)", 230, 40, 150, 40);
        drawRoundBox(g, "Service Layer", 420, 40, 150, 40);
        drawRoundBox(g, "Data Access (DAO)", 610, 40, 150, 40);

        // Lifeline vertical lines
        g.setStroke(new BasicStroke(1, BasicStroke.CAP_BUTT, BasicStroke.JOIN_MITER, 10, new float[]{5}, 0));
        g.drawLine(115, 80, 115, 520);
        g.drawLine(305, 80, 305, 520);
        g.drawLine(495, 80, 495, 520);
        g.drawLine(685, 80, 685, 520);

        // Sequence Messages (Arrows)
        g.setStroke(new BasicStroke(1.5f));
        g.setFont(new Font("Arial", Font.PLAIN, 10));

        // 1. Checkout Click -> API
        drawArrow(g, 115, 130, 305, 130);
        g.drawString("1. AJAX POST (JSON Order Details)", 125, 122);

        // 2. API -> Service (placeOrder)
        drawArrow(g, 305, 190, 495, 190);
        g.drawString("2. Validate details & calculate totals", 315, 182);

        // 3. Service -> DAO (create Order)
        drawArrow(g, 495, 250, 685, 250);
        g.drawString("3. Call create() with SQL bind parameters", 505, 242);

        // 4. Database execution return (Success)
        g.setStroke(new BasicStroke(1, BasicStroke.CAP_BUTT, BasicStroke.JOIN_MITER, 10, new float[]{4}, 0));
        drawArrow(g, 685, 310, 495, 310);
        g.drawString("4. Return new Database Order ID", 515, 302);

        // 5. Service -> API return
        drawArrow(g, 495, 370, 305, 370);
        g.drawString("5. Return processed Order Entity", 325, 362);

        // 6. API -> Browser UI
        drawArrow(g, 305, 430, 115, 430);
        g.drawString("6. Send JSON response (success, order_id)", 125, 422);

        // 7. UI update
        g.setStroke(new BasicStroke(1.5f));
        g.drawArc(70, 460, 45, 40, 90, 270);
        drawArrow(g, 115, 500, 115, 500); // end point
        g.drawString("7. Update tracking page UI", 125, 480);

        ImageIO.write(img, "png", new File("../report_gen/assets/fig5_1.png"));
    }
}
