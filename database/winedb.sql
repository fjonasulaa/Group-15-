CREATE TABLE customer (
  customerID INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  firstName VARCHAR(100) NOT NULL,
  surname VARCHAR(100) NOT NULL,
  dateOfBirth DATE,
  addressLine VARCHAR(255),
  postcode VARCHAR(20),
  email VARCHAR(255) NOT NULL,
  phoneNumber VARCHAR(20),
  passwordHash VARCHAR(255) NOT NULL
);

CREATE TABLE wines (
  wineId INT NOT NULL PRIMARY KEY,
  wineName VARCHAR(100) NOT NULL,
  wineRegion VARCHAR(100),
  ingredients TEXT,
  country VARCHAR(100),
  category VARCHAR(100),
  price DECIMAL(7,2) NOT NULL,
  description TEXT,
  imageUrl VARCHAR(500),
  stock INT UNSIGNED
);
ALTER TABLE wines CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE orders (
  orderId INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  customerId INT NOT NULL,
  orderDate DATE NOT NULL DEFAULT CURRENT_DATE,
  totalAmount DECIMAL(7,2) NOT NULL,
  FOREIGN KEY (customerId) REFERENCES customer(customerID)
);

CREATE TABLE orderswines (
  ordersWinesId INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  orderId INT NOT NULL,
  wineId INT NOT NULL,
  quantity INT NOT NULL,
  FOREIGN KEY (orderId) REFERENCES orders(orderId),
  FOREIGN KEY (wineId) REFERENCES wines(wineId)
);

CREATE TABLE payment (
  paymentId INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  orderId INT NOT NULL,
  method VARCHAR(100) NOT NULL,
  amount DECIMAL(7,2) NOT NULL,
  paymentStatus VARCHAR(100) NOT NULL,
  transactionTimestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (orderId) REFERENCES orders(orderId)
);

CREATE TABLE reviews (
  reviewId INT NOT NULL PRIMARY KEY,
  customerId INT NOT NULL,
  wineId INT NOT NULL,
  stars INT NOT NULL,
  reviewText TEXT,
  FOREIGN KEY (customerId) REFERENCES customer(customerID),
  FOREIGN KEY (wineId) REFERENCES wines(wineId)
);

CREATE TABLE shipping (
  shippingId INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  orderId INT NOT NULL,
  deliveryType VARCHAR(100) NOT NULL,
  carrier VARCHAR(100),
  trackingNumber VARCHAR(100),
  shippingStatus VARCHAR(100) NOT NULL,
  shippingDate DATE,
  estimatedDelivery DATE,
  FOREIGN KEY (orderId) REFERENCES orders(orderId)
);


--INSERT QUERIES--
--(If import does not work, copy and paste each query into the database)--

INSERT INTO wines (wineId, wineName, wineRegion, ingredients, country, category, price, description, imageUrl) VALUES
(1, 'Marchesi Antinori Tignanello', 'Tuscany (Toscana IGT)', 'Predominantly Sangiovese (~78%), with Cabernet Sauvignon (~18%) and Cabernet Franc (~4%)', 'Italy', 'Red Wine', 155.00, 'Tignanello is considered a milestone in Italian winemaking. It was the first modern red wine in Chianti Classico to be aged in barriques and blended with non-traditional varieties like Cabernet. It is intensely ruby red, bold and structured, with flavors of red fruit, spice, and oak. Produced exclusively from the Tignanello vineyard (limestone-rich soils, southwest exposure), it represents innovation and excellence in Tuscan viticulture.', 'tignanello.jpg'),

(2, 'Opus One', 'Oakville AVA, Napa Valley, California', 'Primarily Cabernet Sauvignon, with smaller amounts of Merlot, Cabernet Franc, Malbec, and Petit Verdot', 'United States', 'Red Wine', 375.00, 'Opus One was founded in 1978, with the first vintage released in 1979. Originally named NapaMedoc, it became Opus One in 1982. Aged 18 months in 100% new French oak barrels and another 18 months in bottle, the wine shows deep ruby color, aromas of cassis, blackberry, and spice, with a silky texture and long finish. Production is limited (about 25,000 cases), making it highly collectible. A second wine, Overture, is also produced using Bordeaux varietals across vintages.', 'opus.jpg'),

(3, 'Penfolds Grange', 'South Australia', 'Predominantly Shiraz (Syrah), typically blended with a small percentage of Cabernet Sauvignon', 'Australia', 'Red Wine', 550.00, 'First produced experimentally in 1951 by Max Schubert, Penfolds Grange was inspired by Bordeaux techniques. Known for its bold, full-bodied style with flavors of black fruits, liquorice, vanilla, dark chocolate, and spice, it is aged in new American oak barrels, contributing to its richness and longevity. Listed as a Heritage Icon of South Australia, the wine has been produced without interruption since 1951 and is highly prized at auction.', 'grange.jpg'),

(4, 'Château Margaux (Grand Vin)', 'Margaux, Médoc, Bordeaux', 'Primarily Cabernet Sauvignon, with Merlot, Petit Verdot, and Cabernet Franc', 'France', 'Red Wine', 980.00, 'Château Margaux is renowned for its refined aromatic qualities, silky tannins, and balance of elegance and power. The estate spans 650 acres, with extensive red vineyards and some Sauvignon Blanc. Red wines are aged 18–24 months in new oak. Considered one of the world’s most collectible wines, the Grand Vin is compared to First Growths like Lafite, Latour, Mouton Rothschild, and Haut-Brion. The château also produces Pavillon Rouge, Margaux de Château Margaux, and Pavillon Blanc.', 'margaux.jpg');

INSERT INTO wines (wineId, wineName, wineRegion, ingredients, country, category, price, description, imageUrl) VALUES
(5, 'Soave Classico', 'Veneto, Italy (Classico zone)', 'At least 70% Garganega, often blended with Trebbiano di Soave or small amounts of Chardonnay', 'Italy', 'White Wine', 37.00, 'Soave Classico wines are dry, still whites with aromas of citrus, white flowers, almond, and stone fruit. The volcanic-limestone soils of the Classico zone impart minerality and crisp acidity. Styles vary from light and sprightly to rich and full-bodied depending on producer and vineyard site. Top producers like Pieropan, Gini, and Suavia create age-worthy examples that develop honeyed, nutty complexity over time.', 'Classico.jpg'),

(6, 'Sauvignon Blanc', 'Global (origin: Loire Valley & Bordeaux)', '100% Sauvignon Blanc (sometimes blended with Semillon in Bordeaux)', 'France', 'White Wine', 51.00, 'Sauvignon Blanc is known for gooseberry, grapefruit, lime, passion fruit, and herbal flavors with high acidity and no tannins. Aromas may be grassy, mineral, or flinty, especially in Loire Valley styles like Sancerre and Pouilly-Fumé. Grown globally (New Zealand, California, Chile, South Africa), it pairs excellently with goat cheese, seafood, herb-driven dishes, and Thai or Vietnamese cuisine.', 'blanc.jpg'),

(7, 'Pinot Grigio', 'Northeast Italy (Veneto, Friuli, Trentino-Alto Adige); originated in Burgundy, France', '100% Pinot Grigio (Pinot Gris)', 'Italy', 'White Wine', 35.00, 'Pinot Grigio produces dry white wines with high acidity, light to medium body, and flavors of citrus, green apple, pear, and white peach. Italian styles are crisp and refreshing, while Alsace Pinot Gris is richer and fuller-bodied with honeyed or spicy notes. As a widely grown global variety, it pairs well with seafood, salads, pasta, and Mediterranean dishes.', 'Grigio.avif'),

(8, 'Chablis Premier Cru', 'Chablis, Burgundy', '100% Chardonnay', 'France', 'White Wine', 62.00, 'Chablis Premier Cru wines are known for crisp acidity, minerality, and pure fruit expression. Aromas include green apple, citrus, white flowers, and flinty notes. Aged in stainless steel or neutral oak to emphasize freshness and terroir, these wines come from Premier Cru vineyard sites such as Montée de Tonnerre, Fourchaume, Vaillons, and Montmains. Compared to standard Chablis, they offer greater depth, complexity, and ageing potential.', 'Chablis.jpg');

INSERT INTO wines (wineId, wineName, wineRegion, ingredients, country, category, price, description, imageUrl) VALUES
(9, 'Rock Angel Rosé', 'Côtes de Provence', 'Primarily Grenache and Rolle (Vermentino), sometimes blended with Cinsault', 'France', 'Rosé Wine', 35.00, 'Rock Angel Rosé is produced at Château d’Esclans, the estate behind Whispering Angel. Vinified partly in demi-muids (600L barrels) and stainless steel, it shows subtle oak complexity. Tasting notes include red currants, candied raspberries, strawberries, peaches, citrus, and a mineral edge. Dry, smooth, and elegant, it offers a richer body and longer finish than Whispering Angel, with an ABV of about 13–13.5%.', 'angel.avif'),

(10, 'Clos Mireille', 'La Londe-les-Maures, Côtes de Provence', 'Rosé: Grenache, Cinsault, Syrah, Cabernet Sauvignon; Blanc: Sémillon and Rolle (Vermentino)', 'France', 'Rosé Wine', 48.00, 'Clos Mireille is an estate acquired by Marcel Ott in 1936. The Rosé is known for precision and finesse with flavors of white cherry, nectarine, apricot, citrus, and mineral notes, grown in clay-schist soils. The Blanc is rare and distinctive, showing saline freshness, tropical and citrus fruit, creaminess, and spice. Both wines reflect the estate’s coastal terroir and meticulous production.', 'Mireille.jpg'),

(11, 'Whispering Angel Rosé', 'Côtes de Provence', 'Grenache, Cinsault, Rolle (Vermentino), with small amounts of Mourvèdre and Syrah', 'France', 'Rosé Wine', 99.00, 'Whispering Angel Rosé is pale salmon pink, bone dry, and smooth. Aromas of red berries, citrus, and floral notes lead to a crisp, refreshing palate. Fermented and aged in stainless steel to preserve freshness, it typically sits around 13% ABV. Versatile and widely popular, it pairs well with seafood, salads, poultry, and Mediterranean cuisine.', 'whisper.avif'),

(12, 'Château Minuty Rosé', 'Côtes de Provence', 'Predominantly Grenache, with Cinsault, Syrah, and Tibouren', 'France', 'Rosé Wine', 33.00, 'Château Minuty produces several notable rosés including Minuty M, Minuty Prestige, and Minuty 281. These wines are known for delicate pale color, dry style, and crisp freshness with aromas of citrus, peach, red berries, and floral notes. The estate emphasizes hand-harvesting and strict grape selection. Alcohol content typically ranges from 12.5–13%, pairing well with seafood, Mediterranean cuisine, grilled vegetables, and light salads.', 'Minuty.webp');

INSERT INTO wines (wineId, wineName, wineRegion, ingredients, country, category, price, description, imageUrl) VALUES
(13, 'Royal Tokaji', 'Tokaj, Hungary', 'Primarily Furmint, with Hárslevelű and Muscat Blanc à Petits Grains', 'Hungary', 'Dessert Wine', 390.00, 'Royal Tokaji produces renowned Tokaji Aszú wines (5 and 6 Puttonyos) and the rare Essencia. These wines are made from grapes affected by noble rot, concentrating sugars and flavors. Flavors include apricot, honey, orange peel, dried fruit, and caramel balanced by vibrant acidity. The Puttonyos scale indicates sweetness level, with 6 being the richest. Royal Tokaji also produces dry Furmint wines showcasing the grape’s versatility. ABV typically ranges from 11–13% for Aszú.', 'Tokaji.jpeg'),

(14, 'Constantia (Vin de Constance)', 'Constantia Valley, Cape Town', 'Muscat Blanc à Petits Grains (Muscat de Frontignan)', 'South Africa', 'Dessert Wine', 115.00, 'Vin de Constance is a luxurious sweet wine from the Constantia region, known for fresh acidity, elegance, and balance. It offers flavors of apricot, honey, marmalade, and citrus with lively tartness. The cool maritime climate and granite soils contribute to its minerality and freshness. The region also produces dry Sauvignon Blancs and Bordeaux blends that reflect its coastal terroir. Vin de Constance typically ranges from £60–£90 per 500ml bottle.', 'Constance.jpg'),

(15, 'Avignonesi Occhio di Pernice Vin Santo', 'Montepulciano, Tuscany', '100% Sangiovese (Prugnolo Gentile)', 'Italy', 'Dessert Wine', 237.00, 'Avignonesi Occhio di Pernice is a sweet Tuscan dessert wine made with a unique “madre” yeast culture passed down for generations. Grapes are dried for months on straw mats to create a concentrated must. The wine is aged for 10 years in 50-liter oak barrels, during which significant evaporation occurs, leaving an intensely rich nectar. Tasting notes include dried plums, dates, gingerbread, tobacco, apricot, smoked hazelnut, and Christmas spices with a long, sweet, and spicy finish. ABV ~12.5%.', 'Avignonesi.jpg'),

(16, 'Château dYquem', 'Sauternes, Bordeaux', 'Predominantly Sémillon (~75–80%), with Sauvignon Blanc (~20–25%)', 'France', 'Dessert Wine', 12000.00, 'Château d’Yquem is produced only in exceptional vintages and is considered the greatest sweet wine in the world. Grapes affected by noble rot are harvested berry by berry, concentrating sugars and flavors. Aged for 36 months in new French oak barrels, it shows lush flavors of apricot, honey, orange marmalade, tropical fruit, vanilla, and spice balanced by vibrant acidity. Capable of aging 50–100 years, it develops extraordinary complexity over time.', 'Yquem.jpg');

INSERT INTO wines (wineId, wineName, wineRegion, ingredients, country, category, price, description, imageUrl) VALUES
(17, 'Dom Pérignon', 'Champagne, Épernay', 'Chardonnay and Pinot Noir', 'France', 'Sparkling Wine', 410.00, 'Dom Pérignon is always a vintage Champagne, produced only in exceptional years. Known for fine bubbles, creamy texture, and complex flavors of citrus, brioche, almond, and minerality, with rosé versions adding red fruit depth. P2 and P3 labels represent extended lees ageing for greater richness and intensity. ABV is typically around 12.5%.', 'Perigon.jpg'),

(18, 'Louis Roederer (Cristal, Collection, Brut Premier)', 'Reims, Champagne', 'Primarily Pinot Noir and Chardonnay, with some Pinot Meunier depending on cuvée', 'France', 'Sparkling Wine', 372.00, 'Louis Roederer is one of the few family-owned Champagne houses. Known for precision, elegance, and longevity, with Cristal being among the world’s most collectible Champagnes. Cristal was created in 1876 for Tsar Alexander II, who requested a clear bottle. Tasting notes include citrus, white flowers, brioche, almond, and minerality with fine bubbles and creamy texture. Sustainable viticulture is a major focus for the estate.', 'Louise.avif'),

(19, 'Pommery Cuvée (Cuvée Louise)', 'Champagne, Reims', 'Chardonnay, Pinot Noir, Pinot Meunier', 'France', 'Sparkling Wine', 190.00, 'Cuvée Louise, first released in 1979, is Pommery’s prestige Champagne named after Louise Pommery. Known for fine mousse, creamy texture, and flavors of citrus, white flowers, brioche, and minerality. Aged extensively in Reims chalk cellars for depth and longevity. Rosé versions add red fruit richness. ABV typically around 12.5%.', 'Pommery.jpg'),

(20, 'Bollinger La Grande Année', 'Champagne, Aÿ', 'Predominantly Pinot Noir (65–70%), with Chardonnay (30–35%)', 'France', 'Sparkling Wine', 260.00, 'Bollinger La Grande Année is produced only in exceptional vintages. Fermented entirely in oak barrels and aged on lees for at least 7 years, it is known for richness, complexity, and longevity. Tasting notes include ripe stone fruit, citrus, brioche, hazelnut, and spice with a strong mineral backbone. Rosé versions add red berry notes and extra depth. ABV around 12%.', 'Annee.jpg');

INSERT INTO wines (wineId, wineName, wineRegion, ingredients, country, category, price, description, imageUrl) VALUES
(21, 'Dow’s Vintage Port 2011', 'Douro Valley', 'Touriga Nacional, Touriga Franca, Sousão, and other traditional Douro grapes', 'Portugal', 'Fortified Wine', 195.00, 'Dow’s 2011 Vintage Port is a rich, powerful fortified wine with deep color and firm tannins. Aromas of black fruits, cassis, plum, and spice lead to a full-bodied palate of blackcurrant, cherry, chocolate, and subtle minerality. Known for Dow’s signature dryness, the wine has elegance, structure, and longevity, with the potential to age 40–50 years.', 'Dow.jpg'),

(22, 'Fonseca 1985 Vintage Port', 'Douro Valley', 'Touriga Nacional, Touriga Franca, Tinta Roriz, and other traditional Douro grapes', 'Portugal', 'Fortified Wine', 105.00, 'Fonseca 1985 Vintage Port is deep dark ruby with a broad red rim turning brick-colored at the edge. It shows intense black fruit, dark chocolate, meaty flavors, and firm tannins balanced by purity and elegance. The palate is rich and opulent with creamy texture, long finish, and powerful structure. It remains approachable while offering decades of ageing potential.', 'Fonseca.jpg'),

(23, 'Graham’s 1994 Vintage Port', 'Douro Valley', 'Touriga Nacional, Touriga Franca, Tinta Roriz, and other traditional Douro grapes', 'Portugal', 'Fortified Wine', 125.00, 'Graham’s 1994 Vintage Port is deep, opaque ruby with aromas of ripe blackberry, cassis, plum, and spice. Rich and full-bodied, it shows layers of dark fruit, chocolate, and licorice supported by firm tannins and balanced acidity. With a long, powerful, and elegant finish, it is considered one of the finest Ports of the late 20th century with ageing potential of 40+ years.', 'Grahams.jpg'),

(24, 'Quinta do Vesuvio Vintage Port', 'Douro Valley', 'Touriga Nacional, Touriga Franca, Tinta Roriz, and traditional Douro varieties', 'Portugal', 'Fortified Wine', 476.00, 'Quinta do Vesuvio Vintage Port is a powerful, full-bodied fortified wine with deep color and concentrated aromas of blackberry, cassis, violets, and spice. On the palate it offers dark fruit, chocolate, strong tannins, and fresh minerality. Known for structure and elegance, it has excellent ageing potential of 30–40 years, developing complexity and smoothness over time.', 'Quinta.jpg');

