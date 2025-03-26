-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mar. 25 mars 2025 à 11:36
-- Version du serveur : 8.0.41-0ubuntu0.24.04.1
-- Version de PHP : 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `dbs9616600`
--

-- --------------------------------------------------------

--
-- Structure de la table `-activite-29oct`
--

CREATE TABLE `-activite-29oct` (
  `id-activite` int NOT NULL,
  `id-structure-buyin` int DEFAULT NULL,
  `id-membre` int NOT NULL,
  `titre-activite` varchar(64) NOT NULL DEFAULT '-',
  `date_depart` date DEFAULT '2022-12-31',
  `heure_depart` time NOT NULL,
  `ville` varchar(64) NOT NULL DEFAULT '?',
  `rue` varchar(64) DEFAULT NULL,
  `lng` double DEFAULT NULL,
  `lat` float DEFAULT NULL,
  `icon` varchar(255) NOT NULL,
  `ico-siz` float NOT NULL,
  `photo` varchar(255) NOT NULL,
  `lien` varchar(255) NOT NULL DEFAULT '<a href="/panel/voir-activite.php?uid=	',
  `lien-id` varchar(64) NOT NULL,
  `lien-texte` varchar(255) NOT NULL DEFAULT '">',
  `lien-texte-fin` varchar(255) NOT NULL DEFAULT 'Cliquer Pour Infos',
  `places` int NOT NULL DEFAULT '8',
  `reserves` int DEFAULT NULL,
  `options` int NOT NULL DEFAULT '0',
  `libre` int DEFAULT NULL,
  `commentaire` varchar(128) NOT NULL DEFAULT 'Aucun',
  `buyin` int DEFAULT '25',
  `rake` int DEFAULT '5',
  `bounty` int NOT NULL DEFAULT '0',
  `jetons` int NOT NULL DEFAULT '40000',
  `recave` int NOT NULL DEFAULT '1',
  `addon` int NOT NULL DEFAULT '0',
  `ante` varchar(16) NOT NULL DEFAULT '0',
  `bonus` int DEFAULT NULL,
  `nb-tables` int NOT NULL DEFAULT '1',
  `taille-table1` int NOT NULL DEFAULT '8',
  `id-table1` int NOT NULL,
  `taille-table2` int NOT NULL DEFAULT '0',
  `id-table2` int NOT NULL,
  `taille-table3` int NOT NULL DEFAULT '0',
  `id-table3` int NOT NULL,
  `taille-table4` int NOT NULL DEFAULT '0',
  `id-table4` int NOT NULL,
  `taille-table5` int NOT NULL DEFAULT '0',
  `id-table5` int NOT NULL,
  `taille-table6` int NOT NULL DEFAULT '0',
  `id-table6` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `-admincarapoub`
--

CREATE TABLE `-admincarapoub` (
  `id` int NOT NULL,
  `UserName` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `updationDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `-admindocapoub`
--

CREATE TABLE `-admindocapoub` (
  `id` int NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `updationDate` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `-appointmentapoub`
--

CREATE TABLE `-appointmentapoub` (
  `id` int NOT NULL,
  `doctorSpecialization` varchar(255) DEFAULT NULL,
  `doctorId` int DEFAULT NULL,
  `userId` int DEFAULT NULL,
  `consultancyFees` int DEFAULT NULL,
  `appointmentDate` varchar(255) DEFAULT NULL,
  `appointmentTime` varchar(255) DEFAULT NULL,
  `postingDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `userStatus` int DEFAULT NULL,
  `doctorStatus` int DEFAULT NULL,
  `updationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `-doctorsapoub`
--

CREATE TABLE `-doctorsapoub` (
  `id` int NOT NULL,
  `specilization` varchar(255) DEFAULT NULL,
  `doctorName` varchar(255) DEFAULT NULL,
  `address` longtext,
  `docFees` varchar(255) DEFAULT NULL,
  `contactno` bigint DEFAULT NULL,
  `docEmail` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `creationDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `-doctorspecilizationapoub`
--

CREATE TABLE `-doctorspecilizationapoub` (
  `id` int NOT NULL,
  `specilization` varchar(255) DEFAULT NULL,
  `creationDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `-joueurs`
--

CREATE TABLE `-joueurs` (
  `id` int NOT NULL,
  `nom` varchar(30) DEFAULT NULL,
  `droits` varchar(11) NOT NULL DEFAULT '0',
  `lname` varchar(255) DEFAULT NULL,
  `prenom` varchar(30) DEFAULT NULL,
  `fname` varchar(255) DEFAULT NULL,
  `contactno` varchar(255) NOT NULL DEFAULT '0600000000',
  `posting_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastip` varchar(20) DEFAULT NULL,
  `nbpart` int DEFAULT NULL,
  `nbpart1` int DEFAULT NULL,
  `nbpart2` int DEFAULT NULL,
  `nbpart3` int DEFAULT NULL,
  `nbpart4` int DEFAULT NULL,
  `nbpoints` int DEFAULT '-1',
  `derniere` int DEFAULT NULL,
  `mdp` varchar(30) DEFAULT '1234',
  `password` varchar(255) CHARACTER SET ascii COLLATE ascii_general_ci DEFAULT '1234',
  `CodeV` varchar(64) NOT NULL,
  `verification` tinyint NOT NULL DEFAULT '0',
  `tel` varchar(30) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `co` varchar(255) NOT NULL DEFAULT 'Aucun',
  `photo` varchar(200) NOT NULL,
  `Highlander_Insc` varchar(8) NOT NULL DEFAULT '0',
  `Highlander_Nbpart` int NOT NULL DEFAULT '0',
  `Highlander_NbQual` varchar(8) NOT NULL DEFAULT '0',
  `comp1` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Structure de la table `-passager`
--

CREATE TABLE `-passager` (
  `numpiece` int NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `sexe` varchar(15) NOT NULL,
  `choix_class` varchar(20) NOT NULL DEFAULT 'RÃ©servÃ©e',
  `code_vol` varchar(30) NOT NULL,
  `id` int NOT NULL,
  `etat` varchar(11) NOT NULL DEFAULT 'Actif',
  `ip` varchar(20) NOT NULL,
  `ipmod` varchar(20) NOT NULL,
  `ipsup` varchar(20) NOT NULL,
  `co` varchar(255) DEFAULT NULL,
  `ds` int DEFAULT NULL,
  `clas` int NOT NULL DEFAULT '0',
  `effect` int NOT NULL DEFAULT '0',
  `points` int NOT NULL DEFAULT '0',
  `recave` int NOT NULL DEFAULT '0',
  `gain` int NOT NULL DEFAULT '0',
  `id_vol` int NOT NULL DEFAULT '0',
  `id_joueur` int NOT NULL DEFAULT '0',
  `Highlander_Insc` varchar(8) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL DEFAULT '0',
  `Highlander_Nbpart` int NOT NULL DEFAULT '0',
  `Highlander_NbQual` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `-passagerapoub`
--

CREATE TABLE `-passagerapoub` (
  `numpiece` int NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `sexe` varchar(15) NOT NULL,
  `choix_class` varchar(20) NOT NULL DEFAULT 'RÃ©servÃ©e',
  `code_vol` varchar(30) NOT NULL,
  `id` int NOT NULL,
  `etat` varchar(11) NOT NULL DEFAULT 'Actif',
  `ip` varchar(20) NOT NULL,
  `ipmod` varchar(20) NOT NULL,
  `ipsup` varchar(20) NOT NULL,
  `co` varchar(255) DEFAULT NULL,
  `ds` int DEFAULT NULL,
  `clas` int NOT NULL DEFAULT '0',
  `effect` int NOT NULL DEFAULT '0',
  `points` int NOT NULL DEFAULT '0',
  `recave` int NOT NULL DEFAULT '0',
  `gain` int NOT NULL DEFAULT '0',
  `id_vol` int NOT NULL DEFAULT '0',
  `id_joueur` int NOT NULL DEFAULT '0',
  `Highlander_Insc` varchar(8) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL DEFAULT '0',
  `Highlander_Nbpart` int NOT NULL DEFAULT '0',
  `Highlander_NbQual` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `-resultatsapoub`
--

CREATE TABLE `-resultatsapoub` (
  `id` int NOT NULL,
  `id_partie` int NOT NULL,
  `id_joueur` int NOT NULL,
  `classement` int NOT NULL,
  `effectif` int NOT NULL,
  `points` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Structure de la table `-siege`
--

CREATE TABLE `-siege` (
  `id-siege` int NOT NULL,
  `dispo` int NOT NULL DEFAULT '1',
  `id-membre` int NOT NULL DEFAULT '999',
  `commentaire` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `-tbladmin`
--

CREATE TABLE `-tbladmin` (
  `ID` int NOT NULL,
  `AdminName` varchar(120) DEFAULT NULL,
  `UserName` varchar(50) DEFAULT NULL,
  `MobileNumber` bigint DEFAULT NULL,
  `Email` varchar(120) DEFAULT NULL,
  `Password` varchar(120) DEFAULT NULL,
  `AdminRegdate` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `-tblanimal`
--

CREATE TABLE `-tblanimal` (
  `ID` int NOT NULL,
  `AnimalName` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `CageNumber` int DEFAULT NULL,
  `FeedNumber` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Breed` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `AnimalImage` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Description` mediumtext COLLATE utf8mb4_general_ci,
  `CreationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `-tblbooking`
--

CREATE TABLE `-tblbooking` (
  `id` int NOT NULL,
  `BookingNumber` bigint DEFAULT NULL,
  `userEmail` varchar(100) DEFAULT NULL,
  `VehicleId` int DEFAULT NULL,
  `FromDate` varchar(20) DEFAULT NULL,
  `ToDate` varchar(20) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `Status` int DEFAULT NULL,
  `PostingDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `LastUpdationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `-tblbrands`
--

CREATE TABLE `-tblbrands` (
  `id` int NOT NULL,
  `BrandName` varchar(120) NOT NULL,
  `CreationDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `-tblmedicalhistory`
--

CREATE TABLE `-tblmedicalhistory` (
  `ID` int NOT NULL,
  `PatientID` int DEFAULT NULL,
  `BloodPressure` varchar(200) DEFAULT NULL,
  `BloodSugar` varchar(200) NOT NULL,
  `Weight` varchar(100) DEFAULT NULL,
  `Temperature` varchar(200) DEFAULT NULL,
  `MedicalPres` mediumtext,
  `CreationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `activite`
--

CREATE TABLE `activite` (
  `id-activite` int NOT NULL,
  `id_challenge` int DEFAULT NULL,
  `id-structure` int DEFAULT '1',
  `id-membre` int NOT NULL DEFAULT '265',
  `titre-activite` varchar(64) DEFAULT NULL,
  `date_depart` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `heure_depart` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ville` varchar(64) DEFAULT NULL,
  `rue` varchar(64) DEFAULT NULL,
  `lng` double DEFAULT '0',
  `lat` float DEFAULT '0',
  `icon` varchar(255) DEFAULT 'wpt',
  `ico-siz` float DEFAULT NULL,
  `photo` varchar(255) DEFAULT 'bg.png',
  `lien` varchar(255) NOT NULL DEFAULT '<a href="/panel/voir-activite.php?uid=	',
  `lien-id` varchar(64) DEFAULT NULL,
  `lien-texte` varchar(255) NOT NULL DEFAULT '"><img src="panel/images/',
  `lien-texte-fin` varchar(1024) NOT NULL DEFAULT '" width="150" height="150" align="center">Cliquer Pour Infos',
  `places` int DEFAULT NULL,
  `reserves` int DEFAULT NULL,
  `options` int DEFAULT '0',
  `libre` int DEFAULT NULL,
  `commentaire` varchar(128) DEFAULT NULL,
  `buyin` int DEFAULT NULL,
  `rake` int DEFAULT NULL,
  `bounty` int DEFAULT NULL,
  `jetons` int DEFAULT NULL,
  `recave` int DEFAULT NULL,
  `addon` int DEFAULT NULL,
  `ante` varchar(16) DEFAULT NULL,
  `bonus` int DEFAULT '0',
  `nb-tables` int NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `activite-29oct`
--

CREATE TABLE `activite-29oct` (
  `id-activite` int NOT NULL,
  `id-structure-buyin` int DEFAULT NULL,
  `id-membre` int NOT NULL,
  `titre-activite` varchar(64) NOT NULL DEFAULT '-',
  `date_depart` date DEFAULT '2022-12-31',
  `heure_depart` time NOT NULL,
  `ville` varchar(64) NOT NULL DEFAULT '?',
  `rue` varchar(64) DEFAULT NULL,
  `lng` double DEFAULT NULL,
  `lat` float DEFAULT NULL,
  `icon` varchar(255) NOT NULL,
  `ico-siz` float NOT NULL,
  `photo` varchar(255) NOT NULL,
  `lien` varchar(255) NOT NULL DEFAULT '<a href="/panel/voir-activite.php?uid=	',
  `lien-id` varchar(64) NOT NULL,
  `lien-texte` varchar(255) NOT NULL DEFAULT '">',
  `lien-texte-fin` varchar(255) NOT NULL DEFAULT 'Cliquer Pour Infos',
  `places` int NOT NULL DEFAULT '8',
  `reserves` int DEFAULT NULL,
  `options` int NOT NULL DEFAULT '0',
  `libre` int DEFAULT NULL,
  `commentaire` varchar(128) NOT NULL DEFAULT 'Aucun',
  `buyin` int DEFAULT '25',
  `rake` int DEFAULT '5',
  `bounty` int NOT NULL DEFAULT '0',
  `jetons` int NOT NULL DEFAULT '40000',
  `recave` int NOT NULL DEFAULT '1',
  `addon` int NOT NULL DEFAULT '0',
  `ante` varchar(16) NOT NULL DEFAULT '0',
  `bonus` int DEFAULT NULL,
  `nb-tables` int NOT NULL DEFAULT '1',
  `taille-table1` int NOT NULL DEFAULT '8',
  `id-table1` int NOT NULL,
  `taille-table2` int NOT NULL DEFAULT '0',
  `id-table2` int NOT NULL,
  `taille-table3` int NOT NULL DEFAULT '0',
  `id-table3` int NOT NULL,
  `taille-table4` int NOT NULL DEFAULT '0',
  `id-table4` int NOT NULL,
  `taille-table5` int NOT NULL DEFAULT '0',
  `id-table5` int NOT NULL,
  `taille-table6` int NOT NULL DEFAULT '0',
  `id-table6` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `activite-1603`
--

CREATE TABLE `activite-1603` (
  `id-activite` int NOT NULL,
  `id-structure` int DEFAULT '1',
  `id-membre` int NOT NULL DEFAULT '265',
  `titre-activite` varchar(64) DEFAULT NULL,
  `date_depart` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end_date` timestamp NULL DEFAULT NULL,
  `heure_depart` time DEFAULT '20:00:00',
  `ville` varchar(64) DEFAULT NULL,
  `rue` varchar(64) DEFAULT NULL,
  `lng` double DEFAULT '0',
  `lat` float DEFAULT '0',
  `icon` varchar(255) DEFAULT 'wpt',
  `ico-siz` float DEFAULT NULL,
  `photo` varchar(255) DEFAULT 'bg.png',
  `lien` varchar(255) NOT NULL DEFAULT '<a href="/panel/voir-activite.php?uid=	',
  `lien-id` varchar(64) DEFAULT NULL,
  `lien-texte` varchar(255) NOT NULL DEFAULT '"><img src="panel/images/',
  `lien-texte-fin` varchar(1024) NOT NULL DEFAULT '" width="150" height="150" align="center">Cliquer Pour Infos',
  `places` int DEFAULT NULL,
  `reserves` int DEFAULT NULL,
  `options` int DEFAULT '0',
  `libre` int DEFAULT NULL,
  `commentaire` varchar(128) DEFAULT NULL,
  `buyin` int DEFAULT NULL,
  `rake` int DEFAULT NULL,
  `bounty` int DEFAULT NULL,
  `jetons` int DEFAULT NULL,
  `recave` int DEFAULT NULL,
  `addon` int DEFAULT NULL,
  `ante` varchar(16) DEFAULT NULL,
  `bonus` int DEFAULT '0',
  `nb-tables` int NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL DEFAULT '0000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `admincar`
--

CREATE TABLE `admincar` (
  `id` int NOT NULL,
  `UserName` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `updationDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `admindoc`
--

CREATE TABLE `admindoc` (
  `id` int NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `updationDate` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `adresse`
--

CREATE TABLE `adresse` (
  `id` int NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `date_ajout` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `identifier` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `adresses`
--

CREATE TABLE `adresses` (
  `id` int NOT NULL,
  `address` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  `latitude` int NOT NULL,
  `longitude` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `appointment`
--

CREATE TABLE `appointment` (
  `id` int NOT NULL,
  `doctorSpecialization` varchar(255) DEFAULT NULL,
  `doctorId` int DEFAULT NULL,
  `userId` int DEFAULT NULL,
  `consultancyFees` int DEFAULT NULL,
  `appointmentDate` varchar(255) DEFAULT NULL,
  `appointmentTime` varchar(255) DEFAULT NULL,
  `postingDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `userStatus` int DEFAULT NULL,
  `doctorStatus` int DEFAULT NULL,
  `updationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `blindes`
--

CREATE TABLE `blindes` (
  `id-blinde` int NOT NULL,
  `ordre` int NOT NULL,
  `nom` varchar(16) DEFAULT NULL,
  `val-sb` int NOT NULL,
  `val-bb` int NOT NULL,
  `pause` int NOT NULL,
  `ante` varchar(16) DEFAULT '0',
  `duree` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `blindes-live`
--

CREATE TABLE `blindes-live` (
  `id` int NOT NULL,
  `id-activite` int DEFAULT NULL,
  `ordre` int DEFAULT NULL,
  `nom` varchar(64) DEFAULT NULL,
  `sb` int NOT NULL DEFAULT '0',
  `bb` int NOT NULL DEFAULT '0',
  `duree` time DEFAULT NULL,
  `fin` datetime DEFAULT NULL,
  `ante` varchar(16) DEFAULT '0',
  `en_pause` int DEFAULT '0',
  `heure_pause` datetime DEFAULT NULL,
  `heure_depause` datetime DEFAULT NULL,
  `delta` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `blindes-live-1603`
--

CREATE TABLE `blindes-live-1603` (
  `id` int NOT NULL,
  `id-activite` int DEFAULT NULL,
  `ordre` int DEFAULT NULL,
  `nom` varchar(64) DEFAULT NULL,
  `sb` int NOT NULL DEFAULT '0',
  `bb` int NOT NULL DEFAULT '0',
  `duree` time DEFAULT NULL,
  `fin` datetime DEFAULT NULL,
  `ante` varchar(16) DEFAULT '0',
  `en_pause` int DEFAULT '0',
  `heure_pause` datetime DEFAULT NULL,
  `heure_depause` datetime DEFAULT NULL,
  `delta` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `challenge`
--

CREATE TABLE `challenge` (
  `id_challenge` int NOT NULL,
  `titre_challenge` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `chal_com` varchar(128) NOT NULL,
  `chal_deb` date NOT NULL,
  `chal_fin` date NOT NULL,
  `chal_org` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `challenge-partie`
--

CREATE TABLE `challenge-partie` (
  `chapar_id` int NOT NULL,
  `chapar_id_chal` int NOT NULL,
  `chapar_id_part` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `collections`
--

CREATE TABLE `collections` (
  `id_collection` int NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `commentaire` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `collections-individu`
--

CREATE TABLE `collections-individu` (
  `id` int NOT NULL,
  `id_col` int NOT NULL,
  `id-indiv` int DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `co` varchar(255) NOT NULL DEFAULT 'Inconnu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `competences`
--

CREATE TABLE `competences` (
  `id` int NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `commentaire` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `competences-individu`
--

CREATE TABLE `competences-individu` (
  `id` int NOT NULL,
  `id-comp` int NOT NULL,
  `id-indiv` int DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `co` varchar(255) NOT NULL DEFAULT 'Inconnu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `doctors`
--

CREATE TABLE `doctors` (
  `id` int NOT NULL,
  `specilization` varchar(255) DEFAULT NULL,
  `doctorName` varchar(255) DEFAULT NULL,
  `address` longtext,
  `docFees` varchar(255) DEFAULT NULL,
  `contactno` bigint DEFAULT NULL,
  `docEmail` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `creationDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `doctorslog`
--

CREATE TABLE `doctorslog` (
  `id` int NOT NULL,
  `uid` int DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `userip` binary(16) DEFAULT NULL,
  `loginTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `logout` varchar(255) DEFAULT NULL,
  `status` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `doctorspecilization`
--

CREATE TABLE `doctorspecilization` (
  `id` int NOT NULL,
  `specilization` varchar(255) DEFAULT NULL,
  `creationDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `events`
--

CREATE TABLE `events` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `eventsgps`
--

CREATE TABLE `eventsgps` (
  `id` int NOT NULL,
  `name` char(60) NOT NULL DEFAULT 'Poker',
  `description` char(255) NOT NULL,
  `icon` varchar(255) NOT NULL DEFAULT 'poker',
  `lat` double NOT NULL,
  `lng` double NOT NULL,
  `t1` varchar(64) NOT NULL,
  `t2` varchar(255) NOT NULL,
  `lien` varchar(255) NOT NULL DEFAULT '<a href="/panel/voir-partie.php?uid=',
  `lien-id` varchar(64) NOT NULL,
  `lien-texte` varchar(255) NOT NULL DEFAULT '">',
  `lien-texte-fin` varchar(255) NOT NULL DEFAULT 'Partie N°',
  `icon-size` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `geocoding_results`
--

CREATE TABLE `geocoding_results` (
  `id_geo` int NOT NULL,
  `addresse` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `lestables`
--

CREATE TABLE `lestables` (
  `id-table` int NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `taillemax` int NOT NULL DEFAULT '8',
  `lieu` varchar(255) DEFAULT NULL,
  `id-siege1` int NOT NULL,
  `id-siege2` int NOT NULL,
  `id-siege3` int NOT NULL,
  `id-siege4` int NOT NULL,
  `id-siege5` int NOT NULL,
  `id-siege6` int NOT NULL,
  `id-siege7` int NOT NULL,
  `id-siege8` int NOT NULL,
  `id-siege9` int NOT NULL,
  `id-siege10` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `loisirs`
--

CREATE TABLE `loisirs` (
  `id` int NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `commentaire` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `loisirs-individu`
--

CREATE TABLE `loisirs-individu` (
  `id` int NOT NULL,
  `id-lois` int NOT NULL,
  `id-indiv` int DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `co` varchar(255) NOT NULL DEFAULT 'Inconnu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `membres`
--

CREATE TABLE `membres` (
  `id-membre` int NOT NULL,
  `id_membre` int DEFAULT NULL,
  `pseudo` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `droits` varchar(11) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '1',
  `fname` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `lname` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `type` varchar(6) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT 'M',
  `lastip` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `nbpoints` int DEFAULT '-1',
  `password` varchar(255) CHARACTER SET ascii COLLATE ascii_general_ci DEFAULT '1234',
  `CodeV` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `verification` tinyint NOT NULL DEFAULT '0',
  `telephone` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '0600000000',
  `email` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT 'test@test.fr',
  `photo` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 't1.jpg',
  `photo-map` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'url(panel/images/',
  `commentaire` int DEFAULT NULL,
  `rue` char(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `ville` char(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `country` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT 'France',
  `longitude` double DEFAULT NULL,
  `latitude` float DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'user-m',
  `ico-siz` float DEFAULT NULL,
  `ico_size` int NOT NULL DEFAULT '100',
  `lien` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '<a href="/panel/voir-membre.php?uid=',
  `lien-id` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `lien-texte` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '">',
  `lien-texte-fin` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'Cliquer Pour Infos',
  `def_nomact` varchar(64) NOT NULL DEFAULT 'Chez ',
  `def_str` int DEFAULT '1',
  `def_nbj` int DEFAULT '8',
  `def_buy` int DEFAULT '10',
  `def_rak` int DEFAULT '0',
  `def_bou` int DEFAULT '0',
  `def_rec` int DEFAULT '1',
  `def_jet` int DEFAULT '30000',
  `def_bon` int DEFAULT '0',
  `def_add` int DEFAULT '0',
  `def_ant` int DEFAULT '0',
  `def_rdv` varchar(64) DEFAULT NULL,
  `def_sta` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `def_com` varchar(128) DEFAULT NULL,
  `association_date` date DEFAULT '1970-01-01',
  `posting_date` date DEFAULT '1970-01-01',
  `naissance_date` date DEFAULT NULL,
  `notif_zero` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Structure de la table `participation`
--

CREATE TABLE `participation` (
  `id-participation` int NOT NULL,
  `id-membre` int NOT NULL,
  `nom-membre` varchar(64) DEFAULT NULL,
  `id-membre-vainqueur` int NOT NULL DEFAULT '0',
  `nom-membre-vainqueur` varchar(64) DEFAULT NULL,
  `id-activite` int NOT NULL,
  `id-siege` int DEFAULT '1',
  `id-table` int DEFAULT '1',
  `id-challenge` int NOT NULL DEFAULT '0',
  `option` varchar(20) NOT NULL DEFAULT 'Réservation',
  `ordre` int NOT NULL DEFAULT '0',
  `position` int NOT NULL DEFAULT '0',
  `valide` varchar(11) NOT NULL DEFAULT 'Actif',
  `commentaire` varchar(255) DEFAULT 'Aucun',
  `classement` int NOT NULL DEFAULT '1',
  `recave` int NOT NULL DEFAULT '0',
  `addon` int NOT NULL DEFAULT '0',
  `tf` int DEFAULT NULL,
  `points` int NOT NULL DEFAULT '0',
  `bounty` int NOT NULL DEFAULT '0',
  `gain` int NOT NULL DEFAULT '0',
  `ds` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ip-ins` varchar(20) DEFAULT '1',
  `ip-mod` varchar(20) DEFAULT '2',
  `ip-sup` varchar(20) DEFAULT '3'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `participation-1603`
--

CREATE TABLE `participation-1603` (
  `id-participation` int NOT NULL,
  `id-membre` int NOT NULL,
  `nom-membre` varchar(64) DEFAULT NULL,
  `id-membre-vainqueur` int NOT NULL DEFAULT '0',
  `nom-membre-vainqueur` varchar(64) DEFAULT NULL,
  `id-activite` int NOT NULL,
  `id-siege` int DEFAULT '1',
  `id-table` int DEFAULT '1',
  `id-challenge` int NOT NULL DEFAULT '0',
  `option` varchar(20) NOT NULL DEFAULT 'Réservation',
  `ordre` int NOT NULL DEFAULT '0',
  `position` int NOT NULL DEFAULT '0',
  `valide` varchar(11) NOT NULL DEFAULT 'Actif',
  `commentaire` varchar(255) DEFAULT 'Aucun',
  `classement` int NOT NULL DEFAULT '1',
  `recave` int NOT NULL DEFAULT '0',
  `addon` int NOT NULL DEFAULT '0',
  `points` int NOT NULL DEFAULT '0',
  `bounty` int NOT NULL DEFAULT '0',
  `gain` int NOT NULL DEFAULT '0',
  `ds` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ip-ins` varchar(20) DEFAULT '1',
  `ip-mod` varchar(20) DEFAULT '2',
  `ip-sup` varchar(20) DEFAULT '3'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `register`
--

CREATE TABLE `register` (
  `ID` int NOT NULL,
  `Username` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `CodeV` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `verification` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `resultats`
--

CREATE TABLE `resultats` (
  `id` int NOT NULL,
  `id_partie` int NOT NULL,
  `id_joueur` int NOT NULL,
  `classement` int NOT NULL,
  `effectif` int NOT NULL,
  `points` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Structure de la table `structure`
--

CREATE TABLE `structure` (
  `id` int NOT NULL,
  `id-structure` int NOT NULL,
  `ordre` int NOT NULL,
  `id-blinde` int NOT NULL,
  `duree` int NOT NULL,
  `ante` varchar(16) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `structure-buyin`
--

CREATE TABLE `structure-buyin` (
  `id-structure-buyin` int NOT NULL,
  `buyin` int NOT NULL DEFAULT '10',
  `rake` int NOT NULL DEFAULT '0',
  `id-stricture-rake` int NOT NULL DEFAULT '1',
  `bounty` int NOT NULL DEFAULT '0',
  `nb-recave` int NOT NULL DEFAULT '0',
  `nb-Jetons` int NOT NULL DEFAULT '25000',
  `bonus-nb-jetons` int NOT NULL DEFAULT '0',
  `Addon` int NOT NULL DEFAULT '0',
  `Addon-nb-jetons` int NOT NULL DEFAULT '25000',
  `ante` int NOT NULL DEFAULT '0',
  `id-structure-ante` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `structure_modele`
--

CREATE TABLE `structure_modele` (
  `id_modele_structure` int NOT NULL,
  `id_orga` int NOT NULL,
  `nom` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  `sb` int DEFAULT NULL,
  `bb` int DEFAULT NULL,
  `heure_fin_recave` datetime DEFAULT NULL,
  `fin_pour_21H` datetime DEFAULT NULL,
  `duree` time NOT NULL,
  `nb_jetons` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `t-map`
--

CREATE TABLE `t-map` (
  `id` int NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `lat` float NOT NULL,
  `lng` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `tblcontactus`
--

CREATE TABLE `tblcontactus` (
  `id` int NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contactno` bigint DEFAULT NULL,
  `message` mediumtext,
  `PostingDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `AdminRemark` mediumtext,
  `LastupdationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `IsRead` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tblcontactusinfo`
--

CREATE TABLE `tblcontactusinfo` (
  `id` int NOT NULL,
  `Address` tinytext,
  `EmailId` varchar(255) DEFAULT NULL,
  `ContactNo` char(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tblcontactusquery`
--

CREATE TABLE `tblcontactusquery` (
  `id` int NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `EmailId` varchar(120) DEFAULT NULL,
  `ContactNumber` char(11) DEFAULT NULL,
  `Message` longtext,
  `PostingDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tblpage`
--

CREATE TABLE `tblpage` (
  `ID` int NOT NULL,
  `PageType` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `PageTitle` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `PageDescription` mediumtext COLLATE utf8mb4_general_ci,
  `Email` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `MobileNumber` bigint DEFAULT NULL,
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tblpages`
--

CREATE TABLE `tblpages` (
  `id` int NOT NULL,
  `PageName` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT '',
  `detail` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Structure de la table `tblpatient`
--

CREATE TABLE `tblpatient` (
  `ID` int NOT NULL,
  `Docid` int DEFAULT NULL,
  `PatientName` varchar(200) DEFAULT NULL,
  `PatientContno` bigint DEFAULT NULL,
  `PatientEmail` varchar(200) DEFAULT NULL,
  `PatientGender` varchar(50) DEFAULT NULL,
  `PatientAdd` mediumtext,
  `PatientAge` int DEFAULT NULL,
  `PatientMedhis` mediumtext,
  `CreationDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tblsubscribers`
--

CREATE TABLE `tblsubscribers` (
  `id` int NOT NULL,
  `SubscriberEmail` varchar(120) DEFAULT NULL,
  `PostingDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tbltestimonial`
--

CREATE TABLE `tbltestimonial` (
  `id` int NOT NULL,
  `UserEmail` varchar(100) NOT NULL,
  `Testimonial` mediumtext NOT NULL,
  `PostingDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tblticforeigner`
--

CREATE TABLE `tblticforeigner` (
  `ID` int NOT NULL,
  `TicketID` varchar(200) DEFAULT NULL,
  `visitorName` varchar(250) DEFAULT NULL,
  `NoAdult` int DEFAULT NULL,
  `NoChildren` int DEFAULT NULL,
  `AdultUnitprice` varchar(50) DEFAULT NULL,
  `ChildUnitprice` varchar(50) DEFAULT NULL,
  `PostingDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tblticindian`
--

CREATE TABLE `tblticindian` (
  `ID` int NOT NULL,
  `TicketID` varchar(100) NOT NULL,
  `visitorName` varchar(255) DEFAULT NULL,
  `NoAdult` int DEFAULT NULL,
  `NoChildren` int DEFAULT NULL,
  `AdultUnitprice` varchar(50) DEFAULT NULL,
  `ChildUnitprice` varchar(50) DEFAULT NULL,
  `PostingDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tbltickettype`
--

CREATE TABLE `tbltickettype` (
  `ID` int NOT NULL,
  `TicketType` varchar(200) DEFAULT NULL,
  `Price` varchar(50) DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tblusers`
--

CREATE TABLE `tblusers` (
  `id` int NOT NULL,
  `FullName` varchar(120) DEFAULT NULL,
  `EmailId` varchar(100) DEFAULT NULL,
  `Password` varchar(100) DEFAULT NULL,
  `ContactNo` char(11) DEFAULT NULL,
  `dob` varchar(100) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `City` varchar(100) DEFAULT NULL,
  `Country` varchar(100) DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `tblvehicles`
--

CREATE TABLE `tblvehicles` (
  `id` int NOT NULL,
  `VehiclesTitle` varchar(150) DEFAULT NULL,
  `VehiclesBrand` int DEFAULT NULL,
  `VehiclesOverview` longtext,
  `PricePerDay` int DEFAULT NULL,
  `FuelType` varchar(100) DEFAULT NULL,
  `ModelYear` int DEFAULT NULL,
  `SeatingCapacity` int DEFAULT NULL,
  `Vimage1` varchar(120) DEFAULT NULL,
  `Vimage2` varchar(120) DEFAULT NULL,
  `Vimage3` varchar(120) DEFAULT NULL,
  `Vimage4` varchar(120) DEFAULT NULL,
  `Vimage5` varchar(120) DEFAULT NULL,
  `AirConditioner` int DEFAULT NULL,
  `PowerDoorLocks` int DEFAULT NULL,
  `AntiLockBrakingSystem` int DEFAULT NULL,
  `BrakeAssist` int DEFAULT NULL,
  `PowerSteering` int DEFAULT NULL,
  `DriverAirbag` int DEFAULT NULL,
  `PassengerAirbag` int DEFAULT NULL,
  `PowerWindows` int DEFAULT NULL,
  `CDPlayer` int DEFAULT NULL,
  `CentralLocking` int DEFAULT NULL,
  `CrashSensor` int DEFAULT NULL,
  `LeatherSeats` int DEFAULT NULL,
  `RegDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `userlog`
--

CREATE TABLE `userlog` (
  `id` int NOT NULL,
  `uid` int DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `userip` binary(16) DEFAULT NULL,
  `loginTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `logout` varchar(255) DEFAULT NULL,
  `status` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `country` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `-activite-29oct`
--
ALTER TABLE `-activite-29oct`
  ADD PRIMARY KEY (`id-activite`),
  ADD KEY `organisateur` (`id-membre`);

--
-- Index pour la table `-admincarapoub`
--
ALTER TABLE `-admincarapoub`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `-admindocapoub`
--
ALTER TABLE `-admindocapoub`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `-appointmentapoub`
--
ALTER TABLE `-appointmentapoub`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `-doctorsapoub`
--
ALTER TABLE `-doctorsapoub`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `-doctorspecilizationapoub`
--
ALTER TABLE `-doctorspecilizationapoub`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `-passagerapoub`
--
ALTER TABLE `-passagerapoub`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk1` (`code_vol`);

--
-- Index pour la table `-resultatsapoub`
--
ALTER TABLE `-resultatsapoub`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idjoueur` (`id_joueur`);

--
-- Index pour la table `activite`
--
ALTER TABLE `activite`
  ADD PRIMARY KEY (`id-activite`);

--
-- Index pour la table `activite-1603`
--
ALTER TABLE `activite-1603`
  ADD PRIMARY KEY (`id-activite`);

--
-- Index pour la table `adresse`
--
ALTER TABLE `adresse`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `adresses`
--
ALTER TABLE `adresses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Index pour la table `blindes-live`
--
ALTER TABLE `blindes-live`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Index pour la table `blindes-live-1603`
--
ALTER TABLE `blindes-live-1603`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `challenge`
--
ALTER TABLE `challenge`
  ADD PRIMARY KEY (`id_challenge`);

--
-- Index pour la table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `geocoding_results`
--
ALTER TABLE `geocoding_results`
  ADD PRIMARY KEY (`id_geo`);

--
-- Index pour la table `membres`
--
ALTER TABLE `membres`
  ADD PRIMARY KEY (`id-membre`),
  ADD UNIQUE KEY `ordre` (`id-membre`);

--
-- Index pour la table `participation`
--
ALTER TABLE `participation`
  ADD PRIMARY KEY (`id-participation`),
  ADD UNIQUE KEY `id-participation` (`id-participation`);

--
-- Index pour la table `participation-1603`
--
ALTER TABLE `participation-1603`
  ADD PRIMARY KEY (`id-participation`),
  ADD UNIQUE KEY `id-participation` (`id-participation`);

--
-- Index pour la table `structure_modele`
--
ALTER TABLE `structure_modele`
  ADD PRIMARY KEY (`id_modele_structure`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `-activite-29oct`
--
ALTER TABLE `-activite-29oct`
  MODIFY `id-activite` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `-admincarapoub`
--
ALTER TABLE `-admincarapoub`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `-admindocapoub`
--
ALTER TABLE `-admindocapoub`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `-appointmentapoub`
--
ALTER TABLE `-appointmentapoub`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `-doctorsapoub`
--
ALTER TABLE `-doctorsapoub`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `-doctorspecilizationapoub`
--
ALTER TABLE `-doctorspecilizationapoub`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `-passagerapoub`
--
ALTER TABLE `-passagerapoub`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `-resultatsapoub`
--
ALTER TABLE `-resultatsapoub`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `activite`
--
ALTER TABLE `activite`
  MODIFY `id-activite` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `activite-1603`
--
ALTER TABLE `activite-1603`
  MODIFY `id-activite` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `adresse`
--
ALTER TABLE `adresse`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `adresses`
--
ALTER TABLE `adresses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `blindes-live`
--
ALTER TABLE `blindes-live`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `blindes-live-1603`
--
ALTER TABLE `blindes-live-1603`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `events`
--
ALTER TABLE `events`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `geocoding_results`
--
ALTER TABLE `geocoding_results`
  MODIFY `id_geo` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `membres`
--
ALTER TABLE `membres`
  MODIFY `id-membre` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `participation`
--
ALTER TABLE `participation`
  MODIFY `id-participation` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `participation-1603`
--
ALTER TABLE `participation-1603`
  MODIFY `id-participation` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `structure_modele`
--
ALTER TABLE `structure_modele`
  MODIFY `id_modele_structure` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `-activite-29oct`
--
ALTER TABLE `-activite-29oct`
  ADD CONSTRAINT `organisateur` FOREIGN KEY (`id-membre`) REFERENCES `membres` (`id-membre`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
