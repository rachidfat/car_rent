-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 17 fév. 2022 à 20:25
-- Version du serveur : 10.4.21-MariaDB
-- Version de PHP : 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `car_rent`
--

-- --------------------------------------------------------

--
-- Structure de la table `announcement`
--

CREATE TABLE `announcement` (
  `id_announcement` int(11) NOT NULL,
  `title` varchar(500) NOT NULL,
  `max_km` int(11) NOT NULL,
  `price_per_day` decimal(10,0) NOT NULL,
  `date_posting` datetime NOT NULL DEFAULT current_timestamp(),
  `id_car` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `announcement`
--

INSERT INTO `announcement` (`id_announcement`, `title`, `max_km`, `price_per_day`, `date_posting`, `id_car`) VALUES
(1, 'LOCATION VOITURE PEUGEOT 3008\n', 1000, '50', '2021-12-04 18:36:46', 12),
(3, 'LOCATION VOITURE PEUGEOT 508', 600, '45', '2021-12-05 22:30:48', 9),
(4, 'LOCATION VOITURE HONDA CIVIC', 500, '100', '2021-12-05 22:32:31', 8),
(5, 'LOCATION VOITURE RENAULT TWINGO', 900, '70', '2021-12-05 22:32:31', 7),
(6, 'LOCATION VOITURE PEUGEOT 5008', 500, '70', '2021-12-05 22:37:56', 14),
(7, 'LOCATION VOITURE MERCEDES AMG', 400, '40', '2021-12-05 22:37:56', 10),
(10, 'LOCATION VOITURE DACIA LOGAN', 1500, '85', '2021-12-05 22:45:23', 6),
(12, 'LOCATION VOITURE RENAULT ZOE', 1000, '120', '2021-12-05 22:49:34', 13),
(13, 'LOCATION VOITURE CITROEN AX', 800, '55', '2021-12-05 22:49:34', 6),
(18, 'LOCATION OPEL ZAFIRA', 1500, '60', '2021-12-29 20:17:28', 5),
(20, 'LOCATION AUDI A7', 1500, '95', '2022-01-31 22:48:56', 20);

-- --------------------------------------------------------

--
-- Structure de la table `book`
--

CREATE TABLE `book` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_announcement` int(11) NOT NULL,
  `date_booking` datetime NOT NULL DEFAULT current_timestamp(),
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `price` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `book`
--

INSERT INTO `book` (`id`, `id_user`, `id_announcement`, `date_booking`, `start_date`, `end_date`, `price`) VALUES
(6, 10, 6, '2021-12-31 12:00:09', '2022-01-01 00:00:00', '2022-01-09 00:00:00', '560'),
(7, 10, 6, '2021-12-31 12:41:40', '2022-01-01 00:00:00', '2022-01-09 00:00:00', '560'),
(8, 10, 7, '2022-01-28 15:39:50', '2022-01-29 00:00:00', '2022-02-04 00:00:00', '240');

-- --------------------------------------------------------

--
-- Structure de la table `car`
--

CREATE TABLE `car` (
  `id` int(11) NOT NULL,
  `marque` varchar(50) DEFAULT NULL,
  `modele` varchar(50) DEFAULT NULL,
  `year` int(11) NOT NULL,
  `descCar` varchar(5000) DEFAULT NULL,
  `id_category` int(11) NOT NULL,
  `id_type` int(11) NOT NULL,
  `id_energy` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `seat_number` int(11) NOT NULL,
  `doors_number` int(11) NOT NULL,
  `counter_km` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `car`
--

INSERT INTO `car` (`id`, `marque`, `modele`, `year`, `descCar`, `id_category`, `id_type`, `id_energy`, `id_user`, `seat_number`, `doors_number`, `counter_km`) VALUES
(3, 'Peugeot', '508', 2020, 'Carrosserie cabossée. Bonne mécanique. Je ne fais pas de location en dessous de 90€!\nPour les locations courtes, cherchez sur ouicarconnect, il n y a pas autant de charges de service et le prix peut être inferieur a 90€.\nPrivilegiez les réservations avec prise du véhicule le soir, quitte à reserver depuis la veille du jour qui vous intéresse.\nDe même pas de retours a 21h30 svp ?! Le lendemain ça sera tres bien. Merci !', 4, 1, 5, 10, 5, 4, 10000),
(5, 'Opel', 'Zafira', 2021, 'Je mets à disposition cette petite choupette très propre et fiable.\n\nIdéale pour les petits trajets et facile à garer. Elle est disponible à proximité du métro Saint-Denis porte de Paris ligne13. Possibilité de vous la livrer (service facturé séparément selon le lieu de livraison ). Propriétaire très souple et conscient qu\'il puisse y avoir quelques rayures au retour de la voiture ..mais pas très regardant :-)\n\nBonne route !', 3, 12, 1, 10, 4, 2, 15000),
(6, 'Citroën', 'Ax', 2019, 'Bonjour je loue ma Citroën AX pas très chère qui roule très bien ! . Le coffre ne fonctionne pas mais vous pouvez charger par la banquette arrière. N’hésitez pas à poser vos questions je répond rapidement.\nCordialement', 2, 5, 4, 7, 5, 5, 230000),
(7, 'Renault', 'Twingo', 2021, 'Entretien à jour réalisé par Renault, climatisation, régulateur et limiteur de vitesse, système start & stop, coffre bonne capacité, véhicule propre et à l’aise pour les déplacements en agglomération et hors agglomération. Excellente tenue de route.\n\n\nHoraires flexibles et possibilité de livraison à domicile pour les déplacements supérieurs à 5 jours.\n\nSi ça vous arrange, mon véhicule est aussi disponible :\n43-45 quai jules guesde, 94400 Vitry-sur-Seine', 3, 12, 1, 10, 4, 2, 15000),
(8, 'Honda', 'Civic', 2019, 'Bonjour, je loue ma honda civic 1.5i vtec éco en très bon état de fonctionnement.\nRévision à jour.\nJe réponds rapidement aux demandes de location.\nJe peux venir vous chercher à la gare de Montsoult/Maffliers si vous venez en train.\nLe véhicule peut très bien être loué sur de courtes ou bien de longues périodes.\nCordialement', 2, 5, 2, 10, 5, 5, 23000),
(9, 'Peugeot', '508', 2020, 'Je loue mon véhicule en très bon état de fonctionnement.Révision à jour.Je réponds rapidement aux demandes de location.Je peux venir vous chercher à la gare de Montsoult/Maffliers si vous venez en train.Le véhicule peut très bien être loué sur de courtes ou bien de longues périodes.Cordialement', 4, 6, 1, 8, 4, 4, 157500),
(10, 'Mercedes', 'Amg', 2017, 'Je loue cette superbe Mercedes Amg. Révision à jour. Je réponds rapidement aux demandes de location.Je peux venir vous chercher à la gare de Montsoult/Maffliers si vous venez en train.Le véhicule peut très bien être loué sur de courtes ou bien de longues périodes.Cordialement', 5, 5, 2, 5, 5, 4, 89000),
(12, 'Peugeot', '3008', 2019, '3008 finition supérieure GT Line moteur essence 110ch Stop & Start\nBoîte manuelle 6 vitesses\nMirrorLink et Apple CarPlay disponibles pour retrouver votre mobile à l’écran (dont Waze, Deezer...).\nCompacte, confortable, richement équipée, je ne doute pas qu’elle vous plaira, seul.e, en couple, avec 1 voire 2 enfant(s).\nVéhicule NON FUMEUR, pas d’animaux.\nPas de déménagement d’objets lourds ou contondants (type planche).\nÀ rendre propre intérieur et extérieur (pas forcément lavée mais sans traces/miettes excessives).\nEnfin, merci d’éviter toute tache sur les sièges.\nIL VOUS REVIENT DE GARER LA VOITURE SUR UNE ‘’VRAIE’’ PLACE (pas de place de livraison, marquages vertical et au sol respectés…) car les contraventions sont fréquentes dans le quartier.\nNous pouvons par ailleurs vous prêter jusqu’à 2 sièges auto (1 cat. 2/3 + 1 car. 1/2/3, pour des enfants de 10kg et + (donc à partir d’environ 9 mois), naturellement convertibles en réhausseurs).\nEnfin, comment vous pouvez le voir au travers des commentaires et évaluations, je suis très sérieux et respecte scrupuleusement les règles OuiCar (pas de contournement, pas de caution en parallèle...), ce que je demande naturellement en retour à tout locataire (respect strict de l’âge mini et de la durée de permis mini notamment, tout comme la présentation d’un permis international si votre permis d’origine est hors Europe).', 5, 6, 2, 10, 3, 2, 8000),
(13, 'Renault', 'Zoe', 2018, 'Voiture électrique ayant 200 à 250 km d\'autonomie, adaptée à toutes les routes sauf peut-être les autoroutes où il faut faire attention à la forte augmentation de consommation d\'énergie.', 2, 5, 1, 10, 4, 4, 19500),
(14, 'Peugeot', '5008', 2015, 'Bonjour, Je mets à votre disposition ma super économique petite Sandero Diesel.\r\nC\'est une 5 places, 5 portes qui est en très bonne condition intérieur et extérieur et qui consomme quasi rien.\r\nLa voiture est régulièrement nettoyée et l\'entretiens est toujours à jour.\r\nLes petits plus :\r\n- Bluetooth\r\n-Climatisation\r\n-Commande au volant\r\n-Attelage de remorque\r\nHésitez pas à me solliciter, je réponds à toutes vos questions lorsque j\'en aurais pris connaissance.\r\nPS : SI VOUS AVEZ TROUVÉ CETTE ANNONCE C\'EST QUE LE VÉHICULE EST DISPONIBLE AUX DATES QUE VOUS AVEZ DEMANDÉ ET LA QUASI-TOTALITÉ DES DEMANDES SERONT ACCEPTER.', 1, 6, 3, 5, 5, 4, 100000),
(20, 'Audi', 'A7', 2021, 'Je loue ce A7 faible kilométrage. Montez à bord de l\'Audi A7 Sportback, ce coupé 5 portes fascine par son habitacle luxueux, sa haute technologie et son tempérament dynamique.', 2, 2, 1, 13, 5, 5, 13000);

-- --------------------------------------------------------

--
-- Structure de la table `car_type`
--

CREATE TABLE `car_type` (
  `id` int(11) NOT NULL,
  `car_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `car_type`
--

INSERT INTO `car_type` (`id`, `car_type`) VALUES
(1, 'Coupés'),
(2, 'Berlines'),
(3, 'Hayons'),
(4, 'Break'),
(5, 'Limousine'),
(6, 'Pick-up'),
(7, 'Crossovers'),
(8, 'SUV'),
(9, 'Fourgonnette'),
(10, 'mini-fourgonnette'),
(11, 'Liftback'),
(12, 'Cabriolets'),
(13, 'Minibus'),
(14, 'Roadsters'),
(15, 'Targa');

-- --------------------------------------------------------

--
-- Structure de la table `category_car`
--

CREATE TABLE `category_car` (
  `id` int(11) NOT NULL,
  `category_car` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `category_car`
--

INSERT INTO `category_car` (`id`, `category_car`) VALUES
(1, '4x4'),
(2, 'Routière'),
(3, 'Compacte'),
(4, 'Citadine'),
(5, 'Utilitaire');

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `desc_comment` varchar(500) NOT NULL,
  `stars` decimal(10,0) NOT NULL,
  `date_posting` datetime NOT NULL DEFAULT current_timestamp(),
  `id_user` int(11) NOT NULL,
  `id_car` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`id`, `desc_comment`, `stars`, `date_posting`, `id_user`, `id_car`) VALUES
(1, 'Le véhicule correspondait à mes attentes, véhicules propre, bien entretenu...', '5', '2021-12-19 18:49:59', 15, 6),
(2, 'En excellent état et facile à conduire. Je recommande', '4', '2021-12-19 18:52:13', 13, 6);

-- --------------------------------------------------------

--
-- Structure de la table `energy`
--

CREATE TABLE `energy` (
  `id` int(11) NOT NULL,
  `energy` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `energy`
--

INSERT INTO `energy` (`id`, `energy`) VALUES
(1, 'Essence'),
(2, 'Diesel'),
(3, 'GPL'),
(4, 'Hybride'),
(5, 'Electrique');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `surname` varchar(32) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `cp` varchar(6) NOT NULL,
  `city` varchar(40) NOT NULL,
  `password` varchar(512) NOT NULL,
  `last_connection` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `phone`, `mail`, `address`, `cp`, `city`, `password`, `last_connection`) VALUES
(5, 'Essamami', 'Hamza', '0658250006', 'hamza.essamami.sio@gmail.com', '48 rue Aristide Briand', '91230', 'Montgeron', 'azerty123456', '2022-01-30 11:46:38'),
(6, 'Tobin', 'Michel', '0169191155', 'my_email_89074@gmail.com', '29 Passage Monsieur-le-Prince', '37193', 'Nîmes', 'X265j6I9Zr8HLD7', '2021-12-22 05:55:51'),
(7, 'Brady', 'Louis', '0175407778', 'my_email_65094@gmail.com', '7 Voie Saint-Séverin', '68100', 'Marseille', 'GeDbLAv5WM1rx9H', '2021-12-07 11:09:43'),
(8, 'Lillian', 'Marie', '0144882141', 'my_email_38765@gmail.com', '1 Voie Monsieur-le-Prince', '58457', 'Bourges', 'UPOYDevRRHzVYMp', '2021-12-04 21:43:10'),
(9, 'Maegan', 'Garcia', '0176208110', 'my_email_26504@gmail.com', '27 Allée de Presbourg', '15953', 'Marseille', 'W67QwrpHTvBsD4e', '2021-12-04 21:44:11'),
(10, 'Fathi', 'Rachid', '0159003293', 'rachid@gmail.com', '5 Rue La Boétie', '65629', 'Aix-en-Provence', 'azertyazerty', '2022-02-16 07:54:06'),
(11, 'Shana', 'Laurence', '0117349575', 'my_email_12626@gmail.com', '791 Quai de Presbourg', '34297', 'Marseille', '0LVZp86MXildUIn', '2021-12-04 21:56:21'),
(12, 'Hubert', 'Philippe', '0146048764', 'my_email_62944@gmail.com', '2 Avenue Montorgueil', '33827', 'Montreuil', 'fR1ovxi3rBrjVa8', '2021-12-04 21:57:29'),
(13, 'Gonzalez', 'Nicolas', '0141050809', 'my_email_18084@gmail.com', '8 Boulevard de Vaugirard', '24952', 'Ajaccio', 'xZS5n4RkhwL7LXP', '2022-01-31 12:08:18'),
(14, 'Schneider', 'Jean', '0136134685', 'my_email_86723@gmail.com', '590 Avenue de la Huchette', '72883', 'Sarcelles', 'l3Y2BD2jFdCFayp', '2021-12-04 22:59:35'),
(15, 'Nguyen', 'Jaqueline', '0119394450', 'my_email_27260@gmail.com', '470 Place de la Bûcherie', '59258', 'Marseille', 'ZeJoxUwYNHf0D6U', '2021-12-05 00:20:07'),
(16, 'Prevost', 'Johanna', '0173525876', 'my_email_20924@gmail.com', '614 Rue Bonaparte', '21202', 'Marseille', '9N6G0cNR4QWlyp9', '2021-12-05 00:21:42'),
(17, 'Vasseur', 'Ardella', '0178168249', 'my_email_13293@gmail.com', '69 Impasse des Panoramas', '56093', 'Dijon', 'cm9AEzxkhEygeWV', '2021-12-05 00:25:37'),
(18, 'Robin', 'Philippe', '0114776261', 'my_email_36351@gmail.com', '861 Quai Dauphine', '68813', 'Marseille', 'LjTBlo9ndpLrIw7', '2021-12-05 00:26:49'),
(19, 'Girard', 'Victoria', '0167072758', 'my_email_41735@gmail.com', '686 Allée de Paris', '90172', 'La Seyne-sur-Mer', '5IC2DR5QgedIHTd', '2022-01-28 04:26:43');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `announcement`
--
ALTER TABLE `announcement`
  ADD PRIMARY KEY (`id_announcement`),
  ADD KEY `id_car` (`id_car`);

--
-- Index pour la table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_announcement` (`id_announcement`);

--
-- Index pour la table `car`
--
ALTER TABLE `car`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_type` (`id_type`),
  ADD KEY `id_energy` (`id_energy`),
  ADD KEY `id_category` (`id_category`) USING BTREE,
  ADD KEY `FK_CAR_USER` (`id_user`);

--
-- Index pour la table `car_type`
--
ALTER TABLE `car_type`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `category_car`
--
ALTER TABLE `category_car`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_car` (`id_car`);

--
-- Index pour la table `energy`
--
ALTER TABLE `energy`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `announcement`
--
ALTER TABLE `announcement`
  MODIFY `id_announcement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `book`
--
ALTER TABLE `book`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `car`
--
ALTER TABLE `car`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `car_type`
--
ALTER TABLE `car_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `category_car`
--
ALTER TABLE `category_car`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `energy`
--
ALTER TABLE `energy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `announcement`
--
ALTER TABLE `announcement`
  ADD CONSTRAINT `announcement_ibfk_1` FOREIGN KEY (`id_car`) REFERENCES `car` (`id`);

--
-- Contraintes pour la table `book`
--
ALTER TABLE `book`
  ADD CONSTRAINT `FK_BOOK_ANNOUNCEMENT` FOREIGN KEY (`id_announcement`) REFERENCES `announcement` (`id_announcement`),
  ADD CONSTRAINT `book_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `car`
--
ALTER TABLE `car`
  ADD CONSTRAINT `FK_CAR_USER` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `car_ibfk_1` FOREIGN KEY (`id_type`) REFERENCES `car_type` (`id`),
  ADD CONSTRAINT `car_ibfk_2` FOREIGN KEY (`id_energy`) REFERENCES `energy` (`id`),
  ADD CONSTRAINT `car_ibfk_3` FOREIGN KEY (`id_category`) REFERENCES `category_car` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`id_car`) REFERENCES `car` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
