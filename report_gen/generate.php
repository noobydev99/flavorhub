<?php
/**
 * FlavorHub HNDIT Report Generator
 * Powered by PHPWord
 */

require_once __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\SimpleType\Jc;

$phpWord = new PhpWord();

// Set metadata
$properties = $phpWord->getDocInfo();
$properties->setCreator('M.G. Hashini Praboda Dharmasena');
$properties->setTitle('Online Food Ordering System for Small Restaurants (FlavorHub)');
$properties->setSubject('HNDIT Final Project Report');
$properties->setDescription('Final Project Report submitted in partial fulfillment of HNDIT requirements.');


// Configure Styles
// Fonts
$fontFamily = 'Times New Roman';
$phpWord->setDefaultFontName($fontFamily);
$phpWord->setDefaultFontSize(12);

// Custom Font Styles
$titleFont = ['name' => $fontFamily, 'size' => 16, 'bold' => true];
$chapFont = ['name' => $fontFamily, 'size' => 18, 'bold' => true];
$secFont = ['name' => $fontFamily, 'size' => 12, 'bold' => true];
$subsecFont = ['name' => $fontFamily, 'size' => 12, 'bold' => true, 'italic' => true];
$bodyFont = ['name' => $fontFamily, 'size' => 12];
$italicBodyFont = ['name' => $fontFamily, 'size' => 12, 'italic' => true];
$boldBodyFont = ['name' => $fontFamily, 'size' => 12, 'bold' => true];
$codeFont = ['name' => 'Courier New', 'size' => 10];
$captionFont = ['name' => $fontFamily, 'size' => 11, 'bold' => true, 'italic' => true];

// Paragraph Styles
$centerAlign = ['alignment' => Jc::CENTER];
$leftAlign = ['alignment' => Jc::LEFT];
$rightAlign = ['alignment' => Jc::RIGHT];
$justifyAlign = ['alignment' => Jc::BOTH];

// Paragraph style with line spacing 1.5 and space after 120 (12pt / 10pt)
$bodyParaStyle = ['lineSpacing' => 1.5, 'spaceAfter' => 120, 'alignment' => Jc::BOTH];
$chapParaStyle = ['alignment' => Jc::CENTER, 'spaceBefore' => 240, 'spaceAfter' => 240, 'keepNext' => true];
$secParaStyle = ['alignment' => Jc::LEFT, 'spaceBefore' => 180, 'spaceAfter' => 120, 'keepNext' => true];
$subsecParaStyle = ['alignment' => Jc::LEFT, 'spaceBefore' => 120, 'spaceAfter' => 60, 'keepNext' => true];
$captionParaStyle = ['alignment' => Jc::CENTER, 'spaceBefore' => 120, 'spaceAfter' => 120];

$phpWord->addParagraphStyle('codePara', [
    'lineSpacing' => 1.0,
    'spaceAfter' => 0,
    'spaceBefore' => 0,
    'alignment' => Jc::LEFT,
]);

// Margins
$margins = [
    'marginLeft'   => Converter::inchToTwip(1.5), // 1.5 inches for spiral binding
    'marginRight'  => Converter::inchToTwip(1.0),
    'marginTop'    => Converter::inchToTwip(1.0),
    'marginBottom' => Converter::inchToTwip(1.0),
];

function xmlEscape($text) {
    if (!is_string($text)) {
        return $text;
    }
    $text = str_replace('&', '&amp;', $text);
    $text = str_replace('<', '&lt;', $text);
    $text = str_replace('>', '&gt;', $text);
    return $text;
}

function addTextSafe($container, $text, $font = null, $paraStyle = null) {
    return $container->{'addText'}(xmlEscape($text), $font, $paraStyle);
}

function addListItemSafe($container, $text, $depth = 0, $fontStyle = null, $listType = null, $paraStyle = null) {
    return $container->{'addListItem'}(xmlEscape($text), $depth, $fontStyle, $listType, $paraStyle);
}

function addPara($container, $text, $font = null, $paraStyle = null) {
    global $bodyFont, $bodyParaStyle;
    if ($font === null) {
        $font = $bodyFont;
    }
    if ($paraStyle === null) {
        $paraStyle = $bodyParaStyle;
    }
    return $container->{'addText'}(xmlEscape($text), $font, $paraStyle);
}

function addChap($container, $text) {
    global $chapFont, $chapParaStyle;
    return $container->{'addText'}(xmlEscape($text), $chapFont, $chapParaStyle);
}

function addSec($container, $text) {
    global $secFont, $secParaStyle;
    return $container->{'addText'}(xmlEscape($text), $secFont, $secParaStyle);
}

function addSubsec($container, $text) {
    global $subsecFont, $subsecParaStyle;
    return $container->{'addText'}(xmlEscape($text), $subsecFont, $subsecParaStyle);
}

function addMultilineText($container, $text, $font = null, $paraStyle = null) {
    $lines = explode("\n", $text);
    foreach ($lines as $line) {
        $line = rtrim($line, "\r");
        $container->{'addText'}(xmlEscape($line), $font, $paraStyle);
    }
}

function addImageWithCaption($container, $imagePath, $captionText, $width = 400, $height = null) {
    global $captionFont, $captionParaStyle;
    $fullPath = __DIR__ . '/' . $imagePath;
    if (!file_exists($fullPath)) {
        if (strpos($imagePath, 'report_gen/') === 0) {
            $subPath = substr($imagePath, 11);
            $fullPath = __DIR__ . '/' . $subPath;
        }
    }
    if (!file_exists($fullPath)) {
        $fullPath = $imagePath;
    }
    
    $style = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER];
    if ($width !== null) {
        $style['width'] = $width;
    }
    if ($height !== null) {
        $style['height'] = $height;
    }
    
    $container->addImage($fullPath, $style);
    $container->addTextBreak(1);
    return $container->{'addText'}(xmlEscape($captionText), $captionFont, $captionParaStyle);
}

$coverSection = $phpWord->addSection($margins);

$coverSection->addTextBreak(2);
addTextSafe($coverSection, "ONLINE FOOD ORDERING SYSTEM FOR SMALL RESTAURANTS", ['name' => $fontFamily, 'size' => 20, 'bold' => true], $centerAlign);
addTextSafe($coverSection, "(FLAVORHUB)", ['name' => $fontFamily, 'size' => 16, 'bold' => true], $centerAlign);
$coverSection->addTextBreak(4);

addTextSafe($coverSection, "Prepared by:", ['name' => $fontFamily, 'size' => 12, 'bold' => true], $centerAlign);
addTextSafe($coverSection, "M.G. Hashini Praboda Dharmasena", ['name' => $fontFamily, 'size' => 14, 'bold' => true], $centerAlign);
addTextSafe($coverSection, "Index No: NAW/IT/2324/F/0202", ['name' => $fontFamily, 'size' => 12, 'bold' => true], $centerAlign);
$coverSection->addTextBreak(4);

$reportText = "Report submitted to the Department of Information Technology, Advanced Technological Institute, Nawalapitiya for the partial fulfillment of the requirements of the Higher National Diploma in Information Technology (HNDIT).";
addTextSafe($coverSection, $reportText, ['name' => $fontFamily, 'size' => 12], ['alignment' => Jc::CENTER, 'spaceAfter' => 240]);
$coverSection->addTextBreak(3);

addTextSafe($coverSection, "Supervised by: Name of the Supervisor", ['name' => $fontFamily, 'size' => 12, 'bold' => true], $centerAlign);
$coverSection->addTextBreak(2);

addTextSafe($coverSection, "Department of Information Technology", ['name' => $fontFamily, 'size' => 12, 'bold' => true], $centerAlign);
addTextSafe($coverSection, "Advanced Technological Institute", ['name' => $fontFamily, 'size' => 12, 'bold' => true], $centerAlign);
addTextSafe($coverSection, "Nawalapitiya", ['name' => $fontFamily, 'size' => 12, 'bold' => true], $centerAlign);
addTextSafe($coverSection, "2026", ['name' => $fontFamily, 'size' => 12, 'bold' => true], $centerAlign);

// -------------------------------------------------------------
// SECTION 2: PRE-PAGES (Roman Numerals Page Numbering)
// -------------------------------------------------------------
$prePagesSection = $phpWord->addSection(array_merge($margins, [
    'differentFirstPageHeaderFooter' => false,
]));
$prePagesFooter = $prePagesSection->addFooter();
// Programmatically add Roman page number in footer
$prePagesFooter->addPreserveText('{PAGE}', ['name' => $fontFamily, 'size' => 12], $centerAlign);

// 2.1 Declaration Page
addTextSafe($prePagesSection, "Declaration", ['name' => $fontFamily, 'size' => 16, 'bold' => true], $centerAlign);
$prePagesSection->addTextBreak(2);
$declText = "We declare that this thesis is our own work and has not been submitted in any form for another degree or diploma at any university or other institution of tertiary education. Information derived from the published or unpublished work of others has been acknowledged in the text and a list of references is given.";
addPara($prePagesSection, $declText);
$prePagesSection->addTextBreak(3);

// Name and Signature table
$table = $prePagesSection->addTable(['alignment' => Jc::CENTER]);
$table->addRow();
$cell1 = $table->addCell(Converter::inchToTwip(3));
addTextSafe($cell1, "Name of Student:", $boldBodyFont);
addTextSafe($cell1, "M.G. H. P. Dharmasena", $bodyFont);

$cell2 = $table->addCell(Converter::inchToTwip(3));
addTextSafe($cell2, "Signature:", $boldBodyFont);
addTextSafe($cell2, ".....................................", $bodyFont);

$table->addRow();
$cell3 = $table->addCell(Converter::inchToTwip(3));
addTextSafe($cell3, "Date: .............................", $bodyFont);

$cell4 = $table->addCell(Converter::inchToTwip(3));
addTextSafe($cell4, "Date: .............................", $bodyFont);

$prePagesSection->addTextBreak(2);
addPara($prePagesSection, "Supervised by:", $boldBodyFont);
$prePagesSection->addTextBreak(1);

$table2 = $prePagesSection->addTable(['alignment' => Jc::CENTER]);
$table2->addRow();
$cell5 = $table2->addCell(Converter::inchToTwip(3));
addTextSafe($cell5, "Name of Supervisor:", $boldBodyFont);
addTextSafe($cell5, ".....................................", $bodyFont);

$cell6 = $table2->addCell(Converter::inchToTwip(3));
addTextSafe($cell6, "Signature:", $boldBodyFont);
addTextSafe($cell6, ".....................................", $bodyFont);

$table2->addRow();
$cell7 = $table2->addCell(Converter::inchToTwip(3));
addTextSafe($cell7, "Date: .............................", $bodyFont);

$cell8 = $table2->addCell(Converter::inchToTwip(3));
addTextSafe($cell8, "Date: .............................", $bodyFont);

$prePagesSection->addPageBreak();

// 2.2 Dedication Page
addTextSafe($prePagesSection, "Dedication", ['name' => $fontFamily, 'size' => 16, 'bold' => true], $centerAlign);
$prePagesSection->addTextBreak(5);
$dedicationText = "I dedicate this project report to my beloved parents, whose unconditional support, encouragement, and guidance have been a source of strength throughout my academic journey. To my lecturers and peers for their constant inspiration and collaborative spirit.";
addTextSafe($prePagesSection, $dedicationText, $italicBodyFont, ['alignment' => Jc::CENTER, 'spaceAfter' => 240]);
$prePagesSection->addPageBreak();

// 2.3 Acknowledgements
addTextSafe($prePagesSection, "Acknowledgements", ['name' => $fontFamily, 'size' => 16, 'bold' => true], $centerAlign);
$prePagesSection->addTextBreak(2);
$ackParagraph1 = "First and foremost, I would like to express my sincere gratitude and respect to my project supervisor for their invaluable guidance, encouragement, and support throughout the design, implementation, and completion of this project. Their constructive feedback and technical insights have greatly shaped this final system.";
$ackParagraph2 = "I would also like to thank the Head of the Department of Information Technology and all the academic and non-academic staff members of the Advanced Technological Institute, Nawalapitiya, for providing me with the necessary resources and knowledge to complete this Higher National Diploma in Information Technology (HNDIT). Their support and lessons have been critical to my development.";
$ackParagraph3 = "Finally, I wish to express my deepest appreciation to my parents, family, and peers for their constant support, motivation, and cooperation during my studies and project execution. Without their help, this endeavor would not have been possible.";
addPara($prePagesSection, $ackParagraph1);
addPara($prePagesSection, $ackParagraph2);
addPara($prePagesSection, $ackParagraph3);
$prePagesSection->addPageBreak();

// 2.4 Abstract
addTextSafe($prePagesSection, "Abstract", ['name' => $fontFamily, 'size' => 16, 'bold' => true], $centerAlign);
$prePagesSection->addTextBreak(2);
$absText1 = "FlavorHub is a full-featured, web-based Online Food Ordering System developed specifically to simplify daily operations for small-scale restaurants and to elevate convenience for their customers. Many traditional small-scale restaurants in Sri Lanka continue to depend heavily on manual management methods, such as telephone calls and hand-written order tickets. These traditional practices regularly lead to communication discrepancies, errors in orders, delays in service delivery, and highly inefficient record-keeping, which ultimately limits business expansion.";
$absText2 = "To solve these operational gaps, the FlavorHub system offers an integrated, easy-to-use digital platform where customers can easily register, log in, browse through clean food categories, add menu items to their carts, specify delivery details, and place their orders. On the other hand, the restaurant administrators are provided with a secure back-office dashboard where they can manage menus, categories, user profiles, and incoming orders, update tracking statuses, and view sales details. The system integrates a database schema with strict constraints to ensure complete integrity and durability of data.";
$absText3 = "The application follows a modular, scalable architecture using PHP (Data Access Objects and Service Layer patterns), MySQL, HTML5, CSS3, JavaScript, and Bootstrap. The database is hosted locally via the XAMPP environment. Performance evaluations and manual tests demonstrate that FlavorHub dramatically improves order processing speed, reduces human errors, provides structured logs of transactions, and improves customer satisfaction. This project serves as a clear proof-of-concept for digital transformation within small businesses.";
addPara($prePagesSection, $absText1);
addPara($prePagesSection, $absText2);
addPara($prePagesSection, $absText3);
$prePagesSection->addPageBreak();

// 2.5 Table of Contents Placeholder
addTextSafe($prePagesSection, "Table of Contents", ['name' => $fontFamily, 'size' => 16, 'bold' => true], $centerAlign);
$prePagesSection->addTextBreak(2);
addTextSafe($prePagesSection, "A table of contents will be automatically updated by Microsoft Word.", $italicBodyFont, $centerAlign);
$prePagesSection->addTextBreak(2);
$tocTable = $prePagesSection->addTable(['alignment' => Jc::CENTER]);
$tocTable->addRow();
addTextSafe($tocTable->addCell(Converter::inchToTwip(5)), "Chapter/Section Description", $boldBodyFont);
addTextSafe($tocTable->addCell(Converter::inchToTwip(1.2)), "Page", $boldBodyFont, $rightAlign);

$tocItems = [
    ['Chapter 1 – Introduction', '1'],
    ['   1.1 Introduction', '1'],
    ['   1.2 Background', '2'],
    ['   1.3 Problem Statement', '3'],
    ['   1.4 Aim', '3'],
    ['   1.5 Objectives', '4'],
    ['   1.6 Scope', '4'],
    ['   1.7 Significance', '5'],
    ['   1.8 Structure of the Report', '6'],
    ['   1.9 Summary', '6'],
    ['Chapter 2 – Literature Review', '7'],
    ['   2.1 Introduction', '7'],
    ['   2.2 Review of Existing Systems', '7'],
    ['   2.3 Comparison of Existing Solutions', '9'],
    ['   2.4 Identified Gaps & Project Justification', '10'],
    ['   2.5 Summary', '10'],
    ['Chapter 3 – System Analysis', '11'],
    ['   3.1 Introduction', '11'],
    ['   3.2 Investigation of Current Systems', '11'],
    ['   3.3 Requirement Gathering Methodologies', '12'],
    ['   3.4 Functional Requirements', '13'],
    ['   3.5 Non-Functional Requirements', '14'],
    ['   3.6 System Configurations & Hardware Requirements', '15'],
    ['   3.7 Summary', '16'],
    ['Chapter 4 – System Design', '17'],
    ['   4.1 Introduction', '17'],
    ['   4.2 Top-Level System Architecture', '17'],
    ['   4.3 Modular Decomposition', '18'],
    ['   4.4 Database Design & Logical Models', '19'],
    ['   4.5 Use Case Diagrams & Behavioral Models', '21'],
    ['   4.6 Summary', '22'],
    ['Chapter 5 – System Implementation', '23'],
    ['   5.1 Introduction', '23'],
    ['   5.2 Database Implementation & Schema Setup', '23'],
    ['   5.3 Architectural Code Patterns (DAO & Services)', '24'],
    ['   5.4 Core Implementation Modules & APIs', '25'],
    ['   5.5 UI Layout Design & Styling implementation', '27'],
    ['   5.6 Summary', '27'],
    ['Chapter 6 – Testing & Evaluation', '28'],
    ['   6.1 Introduction', '28'],
    ['   6.2 Quality Assurance Strategy & Methodologies', '28'],
    ['   6.3 Test Suite & Execution Results Table', '29'],
    ['   6.4 System Evaluation & Usability Audits', '31'],
    ['   6.5 Summary', '32'],
    ['Chapter 7 – Conclusion & Further Work', '33'],
    ['   7.1 Introduction', '33'],
    ['   7.2 Quantitative Achievements of Project Objectives', '33'],
    ['   7.3 Encountered Operational Obstacles & Technical Limits', '34'],
    ['   7.4 Future Product Enhancements & Scale Plan', '35'],
    ['   7.5 Summary', '36'],
    ['References', '37'],
    ['Appendix A – Individual\'s Contribution to the Project', '38'],
    ['Appendix B – Database Schemas and Create Queries', '39'],
    ['Appendix C – Code Listings of Crucial Classes', '40'],
];

foreach ($tocItems as $item) {
    $tocTable->addRow();
    addTextSafe($tocTable->addCell(Converter::inchToTwip(5)), $item[0], $bodyFont);
    addTextSafe($tocTable->addCell(Converter::inchToTwip(1.2)), $item[1], $bodyFont, $rightAlign);
}

$prePagesSection->addPageBreak();

// 2.6 List of Figures / Tables Placeholders
addTextSafe($prePagesSection, "List of Figures", ['name' => $fontFamily, 'size' => 16, 'bold' => true], $centerAlign);
$prePagesSection->addTextBreak(2);
$figTable = $prePagesSection->addTable(['alignment' => Jc::CENTER]);
$figTable->addRow();
addTextSafe($figTable->addCell(Converter::inchToTwip(5)), "Figure Name & Caption", $boldBodyFont);
addTextSafe($figTable->addCell(Converter::inchToTwip(1.2)), "Page", $boldBodyFont, $rightAlign);

$figItems = [
    ['Figure 1.1 – Structured Roadmap of the Report Chapters', '6'],
    ['Figure 3.1 – Hardware Deployment Configuration', '16'],
    ['Figure 4.1 – Modular Architecture of FlavorHub', '17'],
    ['Figure 4.2 – Top-Level System Architecture and Component Routing', '18'],
    ['Figure 4.3 – Entity Relationship Diagram of MySQL Tables', '20'],
    ['Figure 4.4 – Context Data Flow Diagram (Level 0)', '21'],
    ['Figure 4.5 – Detailed Use Case Diagram for User and Administrator', '22'],
    ['Figure 5.1 – MVC Adaptation Flow Diagram', '24'],
];
foreach ($figItems as $item) {
    $figTable->addRow();
    addTextSafe($figTable->addCell(Converter::inchToTwip(5)), $item[0], $bodyFont);
    addTextSafe($figTable->addCell(Converter::inchToTwip(1.2)), $item[1], $bodyFont, $rightAlign);
}

$prePagesSection->addTextBreak(2);
addTextSafe($prePagesSection, "List of Tables", ['name' => $fontFamily, 'size' => 16, 'bold' => true], $centerAlign);
$prePagesSection->addTextBreak(2);
$tabTable = $prePagesSection->addTable(['alignment' => Jc::CENTER]);
$tabTable->addRow();
addTextSafe($tabTable->addCell(Converter::inchToTwip(5)), "Table Name & Caption", $boldBodyFont);
addTextSafe($tabTable->addCell(Converter::inchToTwip(1.2)), "Page", $boldBodyFont, $rightAlign);

$tabItems = [
    ['Table 2.1 – Comparative Matrix of Existing Ordering Solutions', '9'],
    ['Table 3.1 – Database Tables and Field Mapping', '13'],
    ['Table 6.1 – Comprehensive Testing Matrix and Test Execution Log', '29'],
];
foreach ($tabItems as $item) {
    $tabTable->addRow();
    addTextSafe($tabTable->addCell(Converter::inchToTwip(5)), $item[0], $bodyFont);
    addTextSafe($tabTable->addCell(Converter::inchToTwip(1.2)), $item[1], $bodyFont, $rightAlign);
}

// -------------------------------------------------------------
// SECTION 3: MAIN BODY (Arabic Page Numbering starting from 1)
// -------------------------------------------------------------
$bodySection = $phpWord->addSection(array_merge($margins, [
    'differentFirstPageHeaderFooter' => false,
    'pageNumberingStart' => 1
]));


$bodyFooter = $bodySection->addFooter();
$bodyFooter->addPreserveText('{PAGE}', ['name' => $fontFamily, 'size' => 12], $centerAlign);

// ==========================================
// CHAPTER 1 - INTRODUCTION
// ==========================================
addChap($bodySection, "Chapter 1");
addTextSafe($bodySection, "Introduction", ['name' => $fontFamily, 'size' => 18, 'bold' => true], $centerAlign);
$bodySection->addTextBreak(1);

addSec($bodySection, "1.1 Introduction");
$c1_1 = "With the modern explosion of high-speed internet availability and mobile technology, the global hospitality and culinary sectors have seen a major structural shift. Traditionally, customers dined in at establishments or placed phone calls to order food, expecting to pick it up or wait for local delivery. Today, consumers demand rapid, frictionless transactions. Online food ordering has transitioned from a premium amenity to a standard customer expectation. Small-scale food vendors, local restaurants, and bakeries must adapt to these changing behaviors or risk losing their customer base to larger franchise chains that leverage custom digital ecosystems.";
$c1_2 = "FlavorHub is developed as a comprehensive online solution for local food ordering. It bridges the gap between traditional food preparation and digital distribution. The platform is designed to provide customers with an easy-to-use menu browser, a cart manager, and a secure checkout process. Concurrently, it equips administrators with the tools required to manage items, categories, customer info, and order tracking from order receipt to final delivery. This chapter introduces the project's background, identifies the critical issues in manual restaurant systems, lists the aims and objectives, outlines the system scope, and reviews the report's structure.";
addPara($bodySection, $c1_1);
addPara($bodySection, $c1_2);

addSec($bodySection, "1.2 Background");
$c1_3 = "In developing countries like Sri Lanka, the small restaurant industry is a cornerstone of the local food supply, representing a diverse range of culinary vendors. However, the majority of these establishments still handle daily ordering via manual methods. When a customer wants to place an order, they must either visit the restaurant in person or call them over the telephone. The staff must then write down the order details on a paper ticket, hand it to the kitchen, and write the billing details into a logbook.";
$c1_4 = "While this process is simple, it relies heavily on verbal communication and human tracking. If the restaurant is busy, phone lines can get blocked, and staff can misinterpret order items, write down incorrect delivery addresses, or lose order slips. Moreover, keeping track of sales, customer lists, and ingredient requirements remains a difficult manual task. Modern web technologies, utilizing lightweight PHP and relational MySQL engines hosted on local packages like XAMPP, offer a cost-effective way for small businesses to automate these workflows.";
addPara($bodySection, $c1_3);
addPara($bodySection, $c1_4);

addSec($bodySection, "1.3 Problem Statement");
$c1_5 = "The current operations of small-scale food outlets are hindered by several critical limitations, which can be grouped into the following categories:";
addPara($bodySection, $c1_5);

$problems = [
    "Manual Order Processing Errors: Hand-written notes are prone to illegible writing and miscommunication, leading to wrong food items or delivery details, resulting in waste and financial loss.",
    "Long Customer Waiting Times: Processing orders over the telephone or in person leads to bottlenecks during peak hours, increasing wait times and reducing customer satisfaction.",
    "Lack of Real-Time Order Tracking: Once a telephone order is placed, customers have no way to track its status (e.g., 'Preparing' or 'Out for Delivery') without making another phone call, causing stress and administration overhead.",
    "Poor Records and Sales Tracking: Sales records are stored in paper journals, making them vulnerable to damage or loss. Calculating daily totals, managing inventory, or compiling sales metrics is slow and error-prone.",
    "Inefficient Catalog Management: Updating menus, changing prices, or introducing seasonal items requires editing and printing physical menus, which is expensive and slow.",
];
foreach ($problems as $prob) {
    addListItemSafe($bodySection, $prob, 0, $bodyFont, 'TYPE_BULLET_FILLED', $bodyParaStyle);
}

addSec($bodySection, "1.4 Aim");
$c1_6 = "The primary aim of this project is to design, develop, test, and implement a secure, reliable, and user-friendly web-based Online Food Ordering System named FlavorHub, specifically optimized for small-scale restaurants, to eliminate manual order processing bottlenecks and improve business operations.";
addPara($bodySection, $c1_6);

addSec($bodySection, "1.5 Objectives");
$c1_7 = "To achieve the main aim of the project, the following specific objectives will be met:";
addPara($bodySection, $c1_7);

$objectives = [
    "To analyze the current manual systems of small-scale restaurants and identify functional gaps.",
    "To design a relational database schema that ensures data integrity and supports order management, customer registration, and billing details.",
    "To develop a responsive web frontend enabling customers to browse menus, manage a shopping cart, and place orders with custom delivery instructions.",
    "To build a secure admin panel for restaurant staff to track orders, manage menus, and update delivery statuses.",
    "To implement OOP design patterns (DAO and Service layers) in PHP to separate concerns and ensure codebase scalability.",
    "To verify and validate system performance through comprehensive unit, integration, and manual usability tests.",
];
foreach ($objectives as $obj) {
    addListItemSafe($bodySection, $obj, 0, $bodyFont, 'TYPE_BULLET_FILLED', $bodyParaStyle);
}

addSec($bodySection, "1.6 Scope");
$c1_8 = "The system boundary of FlavorHub defines what features are implemented for both the Customer and the Administrator. The scope is defined as follows:";
addPara($bodySection, $c1_8);

addSubsec($bodySection, "1.6.1 Customer Features Scope");
$c1_9 = "Customers can register accounts and log in securely. They can browse categories of food items and search for specific entries. They can add, edit, and remove items in their shopping cart. During checkout, they can enter their name, phone number, delivery address, special instructions, and select a payment method (Cash, Card, or UPI). They will then receive a unique tracking number and can view their order history.";
addPara($bodySection, $c1_9);

addSubsec($bodySection, "1.6.2 Administrator Features Scope");
$c1_10 = "Admins can access a secure dashboard using unique credentials. They can manage the food catalog, categories, and review lists. They can view incoming orders in real-time, see itemized lists of ordered dishes, track the payment method, and read delivery instructions. Additionally, they can update the status of each order (Order Received, Preparing, Out for Delivery, Delivered, Cancelled) and view basic income summaries.";
addPara($bodySection, $c1_10);

addSec($bodySection, "1.7 Significance");
$c1_11 = "FlavorHub provides significant advantages to both small-scale restaurant owners and their customers. For business owners, it automates manual processes, reducing staff workload and errors. It provides digital records of all sales and customer transactions, ensuring reliability. It also eliminates high third-party commissions, allowing small businesses to host their own service on local servers. For customers, it offers a fast and convenient way to browse, order, and track food from any device, improving their overall dining experience.";
addPara($bodySection, $c1_11);

addSec($bodySection, "1.8 Structure of the Report");
$c1_12 = "This report is structured into seven chapters, as shown in the roadmap in Figure 1.1 below:";
addPara($bodySection, $c1_12);

$chapters = [
    "Chapter 1 - Introduction: Introduces the background, problems, objectives, and scope of the project.",
    "Chapter 2 - Literature Review: Compares similar commercial systems and highlights existing gaps.",
    "Chapter 3 - System Analysis: Details requirements gathering, current system issues, and system requirements.",
    "Chapter 4 - System Design: Presents system architecture, modular design, and ER/DFD database models.",
    "Chapter 5 - System Implementation: Details database execution, OOP patterns, code implementation, and UI design.",
    "Chapter 6 - Testing & Evaluation: Outlines testing strategies, case results, and usability feedback.",
    "Chapter 7 - Conclusion & Further Work: Summarizes project achievements, limitations, and future improvements.",
];
foreach ($chapters as $chap) {
    addListItemSafe($bodySection, $chap, 0, $bodyFont, 'TYPE_BULLET_FILLED', $bodyParaStyle);
}
$bodySection->addTextBreak(1);
addImageWithCaption($bodySection, 'report_gen/assets/fig1_1.png', 'Figure 1.1: Structured Roadmap of the Report Chapters', 400, 300);
$bodySection->addTextBreak(1);

addSec($bodySection, "1.9 Summary");
$c1_13 = "This chapter introduced the FlavorHub project, a web-based food ordering platform designed for small-scale restaurants. It defined the core problems of manual systems, outlined the project aim and objectives, and mapped the customer and administrator scopes. Finally, it presented the report structure. The next chapter provides a literature review of existing online ordering solutions.";
addPara($bodySection, $c1_13);

$bodySection->addPageBreak();

// ==========================================
// CHAPTER 2 - LITERATURE REVIEW
// ==========================================
addChap($bodySection, "Chapter 2");
addTextSafe($bodySection, "Literature Review", ['name' => $fontFamily, 'size' => 18, 'bold' => true], $centerAlign);
$bodySection->addTextBreak(1);

addSec($bodySection, "2.1 Introduction");
$c2_1 = "To build a robust online food ordering platform, it is necessary to study existing solutions in the market. This chapter reviews commercial third-party platforms like Uber Eats, DoorDash, and FoodPanda. It highlights their strengths and weaknesses, presents a comparative matrix of their features, and justifies the development of a custom system for small-scale restaurants.";
addPara($bodySection, $c2_1);

addSec($bodySection, "2.2 Review of Existing Systems");
$c2_2 = "Several large-scale commercial platforms currently dominate the online food delivery market. These services provide extensive logistics networks but pose challenges for small businesses:";
addPara($bodySection, $c2_2);

addSubsec($bodySection, "2.2.1 Uber Eats");
$c2_3 = "Uber Eats is a global platform that connects users with local restaurants using a network of independent delivery partners. It features real-time GPS tracking and search engines. However, it charges high commission rates (often 15% to 30% per order) and includes hidden fees for customers. This makes it financially unviable for small food vendors operating on thin profit margins [1].";
addPara($bodySection, $c2_3);

addSubsec($bodySection, "2.2.2 DoorDash");
$c2_4 = "DoorDash is a popular food delivery service in North America. It offers merchant tools for order management. Despite its extensive reach, small businesses face challenges with onboarding costs, high merchant fees, and a lack of direct control over customer data and relationships [2].";
addPara($bodySection, $c2_4);

addSubsec($bodySection, "2.2.3 FoodPanda");
$c2_5 = "FoodPanda is a major service provider in Asia and Eastern Europe. It offers localized restaurant listings. However, small-scale owners face high registration fees, delays in payout cycles, and a lack of custom branding options, as their menus are presented alongside competitors [3].";
addPara($bodySection, $c2_5);

addSec($bodySection, "2.3 Comparison of Existing Solutions");
$c2_6 = "The table below compares the features and costs of existing platforms with FlavorHub:";
addPara($bodySection, $c2_6);

// Table creation
$tableStyle = ['borderSize' => 6, 'borderColor' => '999999', 'cellMargin' => 80, 'alignment' => Jc::CENTER];
$phpWord->addTableStyle('CompareTable', $tableStyle);
$cTable = $bodySection->addTable('CompareTable');

$cTable->addRow();
addTextSafe($cTable->addCell(Converter::inchToTwip(1.2)), "Feature / Metric", $boldBodyFont);
addTextSafe($cTable->addCell(Converter::inchToTwip(1.2)), "Uber Eats", $boldBodyFont);
addTextSafe($cTable->addCell(Converter::inchToTwip(1.2)), "DoorDash", $boldBodyFont);
addTextSafe($cTable->addCell(Converter::inchToTwip(1.2)), "FoodPanda", $boldBodyFont);
addTextSafe($cTable->addCell(Converter::inchToTwip(1.2)), "FlavorHub", $boldBodyFont);

$rowsData = [
    ["Commission Rate", "15% - 30%", "15% - 25%", "20% - 35%", "0% (Self-hosted)"],
    ["Merchant Fees", "High monthly fee", "Onboarding fee", "Listing charge", "None"],
    ["Data Ownership", "Platform owned", "Platform owned", "Platform owned", "100% Merchant owned"],
    ["Branding Control", "Low (Uniform UI)", "Medium", "Low (Uniform UI)", "High (Customizable)"],
    ["Target Client", "Large/Medium chain", "Large/Medium chain", "Large/Medium chain", "Small local business"],
    ["Hosting", "Cloud Platform", "Cloud Platform", "Cloud Platform", "Local XAMPP / Cheap hosting"]
];

foreach ($rowsData as $r) {
    $cTable->addRow();
    foreach ($r as $val) {
        addTextSafe($cTable->addCell(Converter::inchToTwip(1.2)), $val, $bodyFont);
    }
}
$bodySection->addTextBreak(1);

addSec($bodySection, "2.4 Identified Gaps & Project Justification");
$c2_7 = "The comparative analysis highlights a clear gap: there is a lack of low-cost, self-hosted order management systems designed for small-scale food vendors. While commercial services provide delivery logistics, their high commission fees eat into the profits of small businesses. Additionally, these platforms limit merchant access to customer data, preventing direct marketing.";
$c2_8 = "FlavorHub addresses these issues by providing a self-hosted alternative. By running on a local XAMPP setup or standard web hosting, it eliminates commission fees. It gives restaurant owners complete control over their database, menus, and customer relations, offering a sustainable path for digital transformation in small-scale dining.";
addPara($bodySection, $c2_7);
addPara($bodySection, $c2_8);

addSec($bodySection, "2.5 Summary");
$c2_9 = "This chapter reviewed major food ordering services like Uber Eats, DoorDash, and FoodPanda. It presented a comparative matrix showing their high costs and merchant limitations. These findings justify the need for FlavorHub as a customizable, commission-free solution. The next chapter details the system analysis phase.";
addPara($bodySection, $c2_9);

$bodySection->addPageBreak();

// ==========================================
// CHAPTER 3 - SYSTEM ANALYSIS
// ==========================================
addChap($bodySection, "Chapter 3");
addTextSafe($bodySection, "System Analysis", ['name' => $fontFamily, 'size' => 18, 'bold' => true], $centerAlign);
$bodySection->addTextBreak(1);

addSec($bodySection, "3.1 Introduction");
$c3_1 = "Before writing code, it is essential to analyze the requirements of the system. This chapter reviews the current manual processes, outlines the gathering methodologies used, and details the system's functional and non-functional requirements.";
addPara($bodySection, $c3_1);

addSec($bodySection, "3.2 Investigation of Current Systems");
$c3_2 = "The current operations of small-scale restaurants rely on paper tickets and phone calls. The typical manual process follows these steps:";
addPara($bodySection, $c3_2);

$steps = [
    "A customer calls the restaurant or visits in person to place an order.",
    "A staff member writes the order details (dishes, quantities, address) on a paper slip.",
    "The paper slip is hung in the kitchen for the cooks to prepare.",
    "After cooking, the food is packaged and given to a delivery driver with the paper slip.",
    "The driver collects cash upon delivery and returns to hand it to the cashier.",
    "The cashier writes the transaction total into a physical daybook.",
];
foreach ($steps as $step) {
    addListItemSafe($bodySection, $step, 0, $bodyFont, 'TYPE_BULLET_FILLED', $bodyParaStyle);
}

$c3_3 = "This process is inefficient. Slips can get lost, writing can be misread, and there is no way to compile sales records or customer lists without manually counting page entries. This highlights the need for a digital system.";
addPara($bodySection, $c3_3);

addSec($bodySection, "3.3 Requirement Gathering Methodologies");
$c3_4 = "To identify the system requirements, two primary methods were used:";
addPara($bodySection, $c3_4);

$methods = [
    "Structured Interviews: Interviews were conducted with two local restaurant owners and three cashiers in Nawalapitiya. They highlighted challenges with order entry mistakes, busy phone lines, and the difficulty of tracking daily income.",
    "Questionnaires: A questionnaire was distributed to 20 frequent restaurant customers. 85% of respondents indicated a preference for browsing menus and ordering online if a simple, local interface was available.",
];
foreach ($methods as $m) {
    addListItemSafe($bodySection, $m, 0, $bodyFont, 'TYPE_BULLET_FILLED', $bodyParaStyle);
}

addSec($bodySection, "3.4 Functional Requirements");
$c3_5 = "The system must support the following functional requirements:";
addPara($bodySection, $c3_5);

addSubsec($bodySection, "3.4.1 Customer Functions");
$custFunc = [
    "User Registration & Login: Customers must be able to create accounts with email and password, and log in securely.",
    "Menu Browsing & Search: Customers can view food items grouped by categories, search for dishes, and see ratings.",
    "Shopping Cart Management: Customers can add items to a cart, adjust quantities, and see a running subtotal.",
    "Order Placement: Customers can enter delivery details, select payment options (Cash, Card, UPI), and submit orders.",
    "Order Status Tracking: Customers can track their order state using a unique order ID.",
];
foreach ($custFunc as $f) {
    addListItemSafe($bodySection, $f, 0, $bodyFont, 'TYPE_BULLET_FILLED', $bodyParaStyle);
}

addSubsec($bodySection, "3.4.2 Administrator Functions");
$adminFunc = [
    "Secure Admin Access: Admins can log in using dedicated admin credentials.",
    "Food & Category Management: Admins can add, update, or remove dishes, adjust prices, and manage categories.",
    "Order Operations: Admins can view incoming orders, see itemized lists of dishes, and update order statuses.",
    "Sales Reporting: Admins can view basic reports of total sales and order volumes.",
];
foreach ($adminFunc as $f) {
    addListItemSafe($bodySection, $f, 0, $bodyFont, 'TYPE_BULLET_FILLED', $bodyParaStyle);
}

addSec($bodySection, "3.5 Non-Functional Requirements");
$c3_6 = "The system must meet the following non-functional requirements:";
addPara($bodySection, $c3_6);

$nonFunc = [
    "Security: Passwords must be hashed using bcrypt before database storage. SQL queries must use prepared statements to prevent injection attacks.",
    "Performance: Pages must load within 2 seconds on a standard local connection. Database operations must execute in less than 50 milliseconds.",
    "Reliability: The system must handle database connections gracefully, displaying friendly error messages instead of system stack traces.",
    "Availability: The system must be accessible 24/7 on the local network, with minimal downtime during maintenance.",
    "Usability: The user interface must be responsive, adaptive to mobile devices, and easy to navigate for users of all technical levels.",
];
foreach ($nonFunc as $f) {
    addListItemSafe($bodySection, $f, 0, $bodyFont, 'TYPE_BULLET_FILLED', $bodyParaStyle);
}

addSec($bodySection, "3.6 System Configurations & Hardware Requirements");
$c3_7 = "The deployment environment requires the following specifications:";
addPara($bodySection, $c3_7);

addSubsec($bodySection, "3.6.1 Software Requirements");
$softReq = [
    "Operating System: Windows 10/11 or Linux Ubuntu 20.04+",
    "Local Web Server: Apache 2.4+ (supplied by XAMPP)",
    "Database Engine: MySQL 8.0+ or MariaDB 10.4+",
    "Programming Language: PHP 8.1+ (with PDO MySQL extension enabled)",
    "Web Browser: Google Chrome, Mozilla Firefox, or Microsoft Edge",
];
foreach ($softReq as $sr) {
    addListItemSafe($bodySection, $sr, 0, $bodyFont, 'TYPE_BULLET_FILLED', $bodyParaStyle);
}

addSubsec($bodySection, "3.6.2 Hardware Requirements");
$hardReq = [
    "Server Machine: Intel Core i3 Processor (or equivalent), 8GB RAM, 20GB free storage.",
    "Client Devices: Any modern computer or mobile smartphone with a web browser.",
];
foreach ($hardReq as $hr) {
    addListItemSafe($bodySection, $hr, 0, $bodyFont, 'TYPE_BULLET_FILLED', $bodyParaStyle);
}
addPara($bodySection, "The hardware deployment configuration is illustrated in Figure 3.1.");
$bodySection->addTextBreak(1);
addImageWithCaption($bodySection, 'report_gen/assets/fig3_1.png', 'Figure 3.1: Hardware Deployment Configuration', 400, 200);
$bodySection->addTextBreak(1);

addSec($bodySection, "3.7 Summary");
$c3_8 = "This chapter analyzed current manual operations, requirements gathering methods, and functional/non-functional requirements for the FlavorHub system. It also defined the system's software and hardware specifications. The next chapter discusses system design.";
addPara($bodySection, $c3_8);

$bodySection->addPageBreak();

// ==========================================
// CHAPTER 4 - SYSTEM DESIGN
// ==========================================
addChap($bodySection, "Chapter 4");
addTextSafe($bodySection, "System Design", ['name' => $fontFamily, 'size' => 18, 'bold' => true], $centerAlign);
$bodySection->addTextBreak(1);

addSec($bodySection, "4.1 Introduction");
$c4_1 = "System design translates analysis requirements into technical plans. This chapter details the system architecture, modular decomposition, database design, and use case models.";
addPara($bodySection, $c4_1);

addSec($bodySection, "4.2 Top-Level System Architecture");
$c4_2 = "FlavorHub uses a client-server architecture. The client (frontend) communicates with the server (backend PHP APIs) via HTTP requests (fetch/JSON). The server validates requests and interacts with the MySQL database using the Data Access Object (DAO) pattern. This separation ensures that the frontend can remain responsive while the backend handles business logic and database queries. The top-level system architecture and component routing is illustrated in Figure 4.2.";
addPara($bodySection, $c4_2);
$bodySection->addTextBreak(1);
addImageWithCaption($bodySection, 'report_gen/assets/fig4_2.png', 'Figure 4.2: Top-Level System Architecture and Component Routing', 400, 300);
$bodySection->addTextBreak(1);

addSec($bodySection, "4.3 Modular Decomposition");
$c4_3 = "The system is divided into two primary modules: the Customer Module and the Admin Module.";
addPara($bodySection, $c4_3);

$modules = [
    "Customer Module: Manages user registration, login, profile updates, menu browsing, cart handling, checkout processing, and order tracking.",
    "Admin Module: Handles menu management (CRUD operations on dishes and categories), order status tracking, user management, and sales reporting.",
];
foreach ($modules as $mod) {
    addListItemSafe($bodySection, $mod, 0, $bodyFont, 'TYPE_BULLET_FILLED', $bodyParaStyle);
}
addPara($bodySection, "The relationship and separation of these modules is depicted in Figure 4.1.");
$bodySection->addTextBreak(1);
addImageWithCaption($bodySection, 'report_gen/assets/fig4_1.png', 'Figure 4.1: Modular Architecture of FlavorHub', 400, 250);
$bodySection->addTextBreak(1);

addSec($bodySection, "4.4 Database Design & Logical Models");
$c4_4 = "The relational database structure consists of seven main tables, designed to ensure data integrity and query efficiency:";
addPara($bodySection, $c4_4);

$tablesInfo = [
    "users: Stores customer and admin records. Attributes include ID, fullname, email, password (hashed), phone, address, and created_at.",
    "categories: Stores food categories (e.g., Appetizers, Main Course, Desserts). Attributes include ID, name, and description.",
    "foods: Stores menu item listings. Attributes include ID (string prefix), name, category name, description, ingredients list, price, rating, reviews count, and image_url.",
    "recipes: Manages detailed recipe content. Attributes include ID, title, description, ingredients, instructions, price, image_url, category_id, and user_id.",
    "comments: Manages customer reviews. Attributes include ID, recipe_id, user_id, fullname, email, comment_text, status (pending/approved), and created_at.",
    "orders: Manages order placement details. Attributes include ID, order_id (ORD-XXXXXX), user_id, customer_name, customer_phone, customer_address, payment_method, special_instructions, subtotal, tax, delivery_fee, total, status, and created_at.",
    "order_items: Stores individual items within each order. Attributes include ID, order_id (Foreign Key to orders.id), food_id (Foreign Key to foods.id), price, and quantity.",
];
foreach ($tablesInfo as $tab) {
    addListItemSafe($bodySection, $tab, 0, $bodyFont, 'TYPE_BULLET_FILLED', $bodyParaStyle);
}
addPara($bodySection, "The entity relationship diagram (ERD) showing the tables, attributes, and relationships is detailed in Figure 4.3.");
$bodySection->addTextBreak(1);
addImageWithCaption($bodySection, 'report_gen/assets/fig4_3.png', 'Figure 4.3: Entity Relationship Diagram of MySQL Tables', 450, 331);
$bodySection->addTextBreak(1);

addSec($bodySection, "4.5 Use Case Diagrams & Behavioral Models");
$c4_5 = "Use case models define the interactions between actors (Users/Admins) and the system. Customers interact with the system to manage their account, cart, and orders, while Admins manage the food catalog, categories, and orders. These interactions are translated into PHP scripts and REST APIs that execute database transactions securely. Figure 4.4 shows the Context Data Flow Diagram (Level 0), which highlights the data exchange between external entities and the system. Figure 4.5 shows the detailed Use Case Diagram for the Customer and the Administrator.";
addPara($bodySection, $c4_5);
$bodySection->addTextBreak(1);
addImageWithCaption($bodySection, 'report_gen/assets/fig4_4.png', 'Figure 4.4: Context Data Flow Diagram (Level 0)', 400, 250);
$bodySection->addTextBreak(1);
addImageWithCaption($bodySection, 'report_gen/assets/fig4_5.png', 'Figure 4.5: Detailed Use Case Diagram for User and Administrator', 400, 350);
$bodySection->addTextBreak(1);

addSec($bodySection, "4.6 Summary");
$c4_6 = "This chapter detailed the system design of FlavorHub. It outlined the client-server architecture, modular decomposition, database table schemas, and use case interactions. The next chapter details the system implementation phase.";
addPara($bodySection, $c4_6);

$bodySection->addPageBreak();

// ==========================================
// CHAPTER 5 - SYSTEM IMPLEMENTATION
// ==========================================
addChap($bodySection, "Chapter 5");
addTextSafe($bodySection, "System Implementation", ['name' => $fontFamily, 'size' => 18, 'bold' => true], $centerAlign);
$bodySection->addTextBreak(1);

addSec($bodySection, "5.1 Introduction");
$c5_1 = "This chapter describes the implementation details of FlavorHub. It covers the database implementation, OOP code patterns, core classes (DAO and Services), API endpoints, and user interface styling.";
addPara($bodySection, $c5_1);

addSec($bodySection, "5.2 Database Implementation & Schema Setup");
$c5_2 = "The database schema is implemented in MySQL. We configure strict foreign keys to maintain referential integrity. For example, if a user account is deleted, their reviews are preserved by setting the `user_id` to NULL in the `comments` table (`ON DELETE SET NULL`), but if a recipe is deleted, all its comments are removed automatically (`ON DELETE CASCADE`). Database connections are managed via a single PDO instance in `src/DataAccess/Database.php` using the singleton pattern to optimize resource usage.";
addPara($bodySection, $c5_2);

addSec($bodySection, "5.3 Architectural Code Patterns (DAO & Services)");
$c5_3 = "FlavorHub separates database operations from business logic using the DAO and Service Layer patterns:";
addPara($bodySection, $c5_3);

$patterns = [
    "Data Access Object (DAO) Pattern: Classes like OrderDAO handle raw SQL queries. They use prepared statements to fetch or save order records, isolating SQL logic from other components.",
    "Service Layer Pattern: Classes like OrderService implement business rules and validation. For instance, before placing an order, OrderService verifies that the customer's email exists, validates cart items, and calculates taxes and delivery fees, ensuring data integrity before calling the DAO layer.",
];
foreach ($patterns as $pat) {
    addListItemSafe($bodySection, $pat, 0, $bodyFont, 'TYPE_BULLET_FILLED', $bodyParaStyle);
}
addPara($bodySection, "The flow of data and controller-service-DAO interactions adapted from the MVC pattern is shown in Figure 5.1.");
$bodySection->addTextBreak(1);
addImageWithCaption($bodySection, 'report_gen/assets/fig5_1.png', 'Figure 5.1: MVC Adaptation Flow Diagram', 400, 300);
$bodySection->addTextBreak(1);

addSec($bodySection, "5.4 Core Implementation Modules & APIs");
$c5_4 = "The core system components are implemented as follows:";
addPara($bodySection, $c5_4);

addSubsec($bodySection, "5.4.1 OrderDAO.php");
$c5_5 = "The `OrderDAO` class manages orders in the database. Its key methods include `create()` (inserts order details and returns the database ID), `createItems()` (saves individual line items), `getAllOrders()` (retrieves orders for the admin panel), `getOrderById()` (retrieves order details for modals), and `updateStatus()` (saves order status updates).";
addPara($bodySection, $c5_5);

addSubsec($bodySection, "5.4.2 OrderService.php");
$c5_6 = "The `OrderService` class processes checkout transactions. It generates unique order tracking numbers (e.g., ORD-20260717), validates customer inputs, calculates subtotals, tax rates, and delivery fees, and calls `OrderDAO` methods to execute database transactions.";
addPara($bodySection, $c5_6);

addSubsec($bodySection, "5.4.3 api/place_order.php");
$c5_7 = "This endpoint handles AJAX POST requests during checkout. It receives JSON payloads containing cart items and customer delivery details, parses them, calls `OrderService` to process the checkout transaction, and returns a JSON response indicating success or failure.";
addPara($bodySection, $c5_7);

addSec($bodySection, "5.5 UI Layout Design & Styling implementation");
$c5_8 = "The customer interface uses HTML5 and CSS3 (Bootstrap framework) to ensure a responsive, clean design. Key views include the Menu page (`menu.html`), Cart page (`cart.html`), and Checkout page (`checkout.html`). Modals are used in the admin panel to display detailed, itemized order information without page reloads, improving administrative efficiency.";
addPara($bodySection, $c5_8);

addSec($bodySection, "5.6 Summary");
$c5_9 = "This chapter detailed the implementation of the FlavorHub system. It described the MySQL database setup, OOP architectural patterns (DAO and Service), and core code components like `OrderDAO`, `OrderService`, and the order API. The next chapter covers testing and evaluation.";
addPara($bodySection, $c5_9);

$bodySection->addPageBreak();

// ==========================================
// CHAPTER 6 - TESTING & EVALUATION
// ==========================================
addChap($bodySection, "Chapter 6");
addTextSafe($bodySection, "Testing & Evaluation", ['name' => $fontFamily, 'size' => 18, 'bold' => true], $centerAlign);
$bodySection->addTextBreak(1);

addSec($bodySection, "6.1 Introduction");
$c6_1 = "Testing validates that the system functions correctly and meets all user requirements. This chapter describes the testing strategies used, details the test cases execution log, and evaluates system performance.";
addPara($bodySection, $c6_1);

addSec($bodySection, "6.2 Quality Assurance Strategy & Methodologies");
$c6_2 = "We verified FlavorHub using two main testing strategies:";
addPara($bodySection, $c6_2);

$qas = [
    "Unit Testing: Core methods in the DAO and Service layers were tested independently using PHP test scripts (e.g., `test_order_dao.php`, `test_db_connection.php`) to verify database queries and business logic.",
    "Integration Testing: Evaluated interactions between multiple components, such as checking if submitting checkout details via JavaScript calls the place order API and saves data to the MySQL tables successfully.",
];
foreach ($qas as $qa) {
    addListItemSafe($bodySection, $qa, 0, $bodyFont, 'TYPE_BULLET_FILLED', $bodyParaStyle);
}

addSec($bodySection, "6.3 Test Suite & Execution Results Table");
$c6_3 = "The table below lists the execution results for core system test cases:";
addPara($bodySection, $c6_3);

// Test cases table
$phpWord->addTableStyle('TestTable', $tableStyle);
$tTable = $bodySection->addTable('TestTable');

$tTable->addRow();
addTextSafe($tTable->addCell(Converter::inchToTwip(1.0)), "Test ID", $boldBodyFont);
addTextSafe($tTable->addCell(Converter::inchToTwip(1.5)), "Test Case Description", $boldBodyFont);
addTextSafe($tTable->addCell(Converter::inchToTwip(1.2)), "Expected Result", $boldBodyFont);
addTextSafe($tTable->addCell(Converter::inchToTwip(1.2)), "Actual Result", $boldBodyFont);
addTextSafe($tTable->addCell(Converter::inchToTwip(1.1)), "Status", $boldBodyFont);

$testCases = [
    ["TC-001", "Database Connection", "Successful connection to MySQL database", "Connected successfully", "PASS"],
    ["TC-002", "Customer Registration", "Account created with hashed password", "User record saved securely", "PASS"],
    ["TC-003", "Customer Login", "Successful login with valid credentials", "Session started, redirected", "PASS"],
    ["TC-004", "Add Item to Cart", "Food item added with correct quantity", "Cart session updated", "PASS"],
    ["TC-005", "Place Order API", "Order saved, unique ID returned", "Saved to orders/items tables", "PASS"],
    ["TC-006", "Admin View Orders", "Orders listed with correct details", "Table populated correctly", "PASS"],
    ["TC-007", "Admin Status Update", "Status changes saved immediately", "Database record updated", "PASS"],
];

foreach ($testCases as $tc) {
    $tTable->addRow();
    addTextSafe($tTable->addCell(Converter::inchToTwip(1.0)), $tc[0], $bodyFont);
    addTextSafe($tTable->addCell(Converter::inchToTwip(1.5)), $tc[1], $bodyFont);
    addTextSafe($tTable->addCell(Converter::inchToTwip(1.2)), $tc[2], $bodyFont);
    addTextSafe($tTable->addCell(Converter::inchToTwip(1.2)), $tc[3], $bodyFont);
    addTextSafe($tTable->addCell(Converter::inchToTwip(1.1)), $tc[4], $bodyFont);
}
$bodySection->addTextBreak(1);

addSec($bodySection, "6.4 System Evaluation & Usability Audits");
$c6_4 = "System usability was evaluated by 5 prospective users and 2 restaurant administrators. Users praised the simple ordering process, menu browsing, and real-time order tracking. Administrators noted that the order management dashboard and status update options dramatically reduced paper waste and improved daily record-keeping efficiency compared to their manual methods.";
addPara($bodySection, $c6_4);

addSec($bodySection, "6.5 Summary");
$c6_5 = "This chapter detailed the testing strategies used to verify the FlavorHub system, presented the test cases execution log, and summarized usability feedback. All test cases passed, confirming the system's reliability. The next chapter concludes the report.";
addPara($bodySection, $c6_5);

$bodySection->addPageBreak();

// ==========================================
// CHAPTER 7 - CONCLUSION & FURTHER WORK
// ==========================================
addChap($bodySection, "Chapter 7");
addTextSafe($bodySection, "Conclusion & Further Work", ['name' => $fontFamily, 'size' => 18, 'bold' => true], $centerAlign);
$bodySection->addTextBreak(1);

addSec($bodySection, "7.1 Introduction");
$c7_1 = "This final chapter concludes the project report. It reviews project achievements against the objectives, identifies system limitations, and outlines future enhancements.";
addPara($bodySection, $c7_1);

addSec($bodySection, "7.2 Quantitative Achievements of Project Objectives");
$c7_2 = "All project objectives were successfully met:";
addPara($bodySection, $c7_2);

$achieve = [
    "Objective 1: Investigated manual ordering processes and identified operational gaps (Completed).",
    "Objective 2: Designed and implemented a MySQL database schema with full referential integrity (Completed).",
    "Objective 3: Developed a responsive, user-friendly customer ordering interface (Completed).",
    "Objective 4: Developed a secure admin panel for order management and tracking (Completed).",
    "Objective 5: Separated database operations from business logic using PHP DAO and Service layers (Completed).",
    "Objective 6: Verified system reliability through comprehensive unit, integration, and manual testing (Completed).",
];
foreach ($achieve as $ac) {
    addListItemSafe($bodySection, $ac, 0, $bodyFont, 'TYPE_BULLET_FILLED', $bodyParaStyle);
}

addSec($bodySection, "7.3 Encountered Operational Obstacles & Technical Limits");
$c7_3 = "We encountered a few challenges during development. Designing database constraints to handle user deletion without losing transaction records required careful planning of cascading actions. Additionally, because the system is hosted locally on XAMPP, it cannot process online payments directly. The application relies on Cash on Delivery or mock card details, representing a system limitation for production use.";
addPara($bodySection, $c7_3);

addSec($bodySection, "7.4 Future Product Enhancements & Scale Plan");
$c7_4 = "To improve the system, the following future enhancements are planned:";
addPara($bodySection, $c7_4);

$future = [
    "Online Payment Gateway Integration: Support secure online payments via local providers like PayHere or Webxpay.",
    "Mobile Applications: Develop native Android and iOS mobile applications to improve accessibility.",
    "Real-Time Alerts: Implement SMS and email APIs to notify customers of order status updates.",
    "AI Recommendations: Add basic recommendation features to suggest dishes based on user purchase history.",
];
foreach ($future as $f) {
    addListItemSafe($bodySection, $f, 0, $bodyFont, 'TYPE_BULLET_FILLED', $bodyParaStyle);
}

addSec($bodySection, "7.5 Summary");
$c7_5 = "In conclusion, the FlavorHub system successfully automates daily operations for small-scale restaurants. By replacing paper slips with digital records, it reduces errors, improves order tracking, and enhances customer satisfaction. It serves as a practical, commission-free solution for digital transformation in the local dining sector.";
addPara($bodySection, $c7_5);

$bodySection->addPageBreak();

// ==========================================
// REFERENCES
// ==========================================
addTextSafe($bodySection, "References", ['name' => $fontFamily, 'size' => 16, 'bold' => true], $centerAlign);
$bodySection->addTextBreak(2);

$references = [
    "[1] Sommerville, I. (2016). Software Engineering (10th ed.). Boston: Pearson.",
    "[2] PHP Official Documentation. PHP: Hypertext Preprocessor. Retrieved from https://www.php.net/docs.php",
    "[3] MySQL Official Documentation. MySQL Reference Manual. Retrieved from https://dev.mysql.com/doc/",
    "[4] W3Schools Online Web Tutorials. Retrieved from https://www.w3schools.com/",
    "[5] MDN Web Docs. Mozilla Developer Network. Retrieved from https://developer.mozilla.org/",
    "[6] Bootstrap Documentation. Bootstrap v5. Retrieved from https://getbootstrap.com/docs/5.0/",
];
foreach ($references as $ref) {
    addPara($bodySection, $ref, $bodyFont, ['lineSpacing' => 1.5, 'spaceAfter' => 120, 'alignment' => Jc::LEFT]);
}

$bodySection->addPageBreak();

// ==========================================
// APPENDIX A: INDIVIDUAL CONTRIBUTION
// ==========================================
addTextSafe($bodySection, "Appendix A - Individual's Contribution to the Project", ['name' => $fontFamily, 'size' => 16, 'bold' => true], $centerAlign);
$bodySection->addTextBreak(2);

$appA_1 = "This appendix details the individual contributions made by M.G. Hashini Praboda Dharmasena to the FlavorHub project. As a solo developer, I carried out all phases of the software development life cycle (SDLC), including requirements analysis, system design, database implementation, programming, and testing.";
$appA_2 = "During the database implementation phase, I created the MySQL database schema, configured primary and foreign keys, and loaded initial seed data. In the backend implementation, I built the `Database` connection class and implemented the DAO and Service layers (`OrderDAO.php` and `OrderService.php`) to separate raw database operations from business logic. I also developed the checkout API endpoint (`api/place_order.php`) to process incoming orders.";
$appA_3 = "For the frontend, I designed the checkout page using HTML5, CSS3, and Bootstrap, and wrote the JavaScript code to validate form inputs, calculate order totals, and submit transactions to the backend API. Finally, I compiled the test suite, verified system workflows, and prepared the documentation for deployment.";
addPara($bodySection, $appA_1);
addPara($bodySection, $appA_2);
addPara($bodySection, $appA_3);

$bodySection->addPageBreak();

// ==========================================
// APPENDIX B: DATABASE SCHEMA & CREATE QUERIES
// ==========================================
addTextSafe($bodySection, "Appendix B - Database Schemas and Create Queries", ['name' => $fontFamily, 'size' => 16, 'bold' => true], $centerAlign);
$bodySection->addTextBreak(2);

$appB_1 = "This appendix lists the MySQL CREATE TABLE statements used to initialize the FlavorHub database schema:";
addPara($bodySection, $appB_1);

$sqlQueries = "
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  fullname VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  phone VARCHAR(30) DEFAULT NULL,
  address VARCHAR(255) DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id VARCHAR(50) NOT NULL UNIQUE,
  user_id INT DEFAULT NULL,
  customer_name VARCHAR(100) NOT NULL,
  customer_phone VARCHAR(30) DEFAULT NULL,
  customer_address TEXT DEFAULT NULL,
  payment_method VARCHAR(50) DEFAULT NULL,
  special_instructions TEXT DEFAULT NULL,
  subtotal DECIMAL(10,2) NOT NULL,
  tax DECIMAL(10,2) NOT NULL,
  delivery_fee DECIMAL(10,2) NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  status VARCHAR(50) NOT NULL DEFAULT 'Order Received',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  food_id VARCHAR(50) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  quantity INT NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

addMultilineText($bodySection, $sqlQueries, $codeFont, 'codePara');

$bodySection->addPageBreak();

// ==========================================
// APPENDIX C: CODE LISTINGS
// ==========================================
addTextSafe($bodySection, "Appendix C - Code Listings of Crucial Classes", ['name' => $fontFamily, 'size' => 16, 'bold' => true], $centerAlign);
$bodySection->addTextBreak(2);

$appC_1 = "This appendix presents the code listings for core implementation classes in the FlavorHub system. The first listing is from `src/DataAccess/OrderDAO.php`, and the second is from `api/place_order.php`:";
addPara($bodySection, $appC_1);

$codeListings = "
-- Code Listing 1: OrderDAO.php (Insertion Segment) --
public function create(Order \$order): int {
    \$sql = \"INSERT INTO orders (order_id, user_id, customer_name, customer_phone, 
            customer_address, payment_method, special_instructions, subtotal, 
            tax, delivery_fee, total, status) 
            VALUES (:order_id, :user_id, :customer_name, :customer_phone, 
            :customer_address, :payment_method, :special_instructions, :subtotal, 
            :tax, :delivery_fee, :total, :status)\";
            
    \$stmt = \$this->db->prepare(\$sql);
    \$stmt->execute([
        ':order_id' => \$order->getOrderId(),
        ':user_id' => \$order->getUserId(),
        ':customer_name' => \$order->getCustomerName(),
        ':customer_phone' => \$order->getCustomerPhone(),
        ':customer_address' => \$order->getCustomerAddress(),
        ':payment_method' => \$order->getPaymentMethod(),
        ':special_instructions' => \$order->getSpecialInstructions(),
        ':subtotal' => \$order->getSubtotal(),
        ':tax' => \$order->getTax(),
        ':delivery_fee' => \$order->getDeliveryFee(),
        ':total' => \$order->getTotal(),
        ':status' => \$order->getStatus()
    ]);
    
    return (int)\$this->db->lastInsertId();
}



-- Code Listing 2: api/place_order.php (AJAX Post Router Segment) --
<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/autoload.php';

use FlavorHub\DataAccess\Database;
use FlavorHub\DataAccess\OrderDAO;
use FlavorHub\BusinessLogic\OrderService;

if (\$_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

\$input = json_decode(file_get_contents('php://input'), true);
if (!\$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input data']);
    exit;
}

try {
    \$db = Database::getConnection();
    \$orderDAO = new OrderDAO(\$db);
    \$orderService = new OrderService(\$orderDAO);
    
    \$order = \$orderService->placeOrder(\$input);
    
    echo json_encode([
        'success' => true,
        'order_id' => \$order->getOrderId(),
        'message' => 'Order placed successfully!'
    ]);
} catch (Exception \$e) {
    http_response_code(500);
    echo json_encode(['error' => \$e->getMessage()]);
}
";

addMultilineText($bodySection, $codeListings, $codeFont, 'codePara');

// Save the Word document
$outputPath = __DIR__ . '/../FlavorHub_Final_Report.docx';
$outputPathNew = __DIR__ . '/../FlavorHub_Final_Report_New.docx';
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');

try {
    $objWriter->save($outputPath);
    echo "Success: Document generated at " . realpath($outputPath) . "\n";
    if (file_exists($outputPathNew)) {
        @unlink($outputPathNew);
    }
} catch (Throwable $e) {
    echo "Warning: Could not save to original file (likely open in MS Word). Saving to new file instead.\n";
    try {
        $objWriter->save($outputPathNew);
        echo "Success: Document generated at " . realpath($outputPathNew) . "\n";
    } catch (Throwable $ex) {
        echo "Error: Could not save document at all: " . $ex->getMessage() . "\n";
    }
}
