
USE MSSQL;

--
-- Anagrafica semplice
--
CREATE TABLE A_Persone (
    PersonID              INT,
    LastName              VARCHAR(255),
    FirstName             VARCHAR(255),
    Email                 VARCHAR(255),
    City                  VARCHAR(255),
    AziendaInCuiLavora_ID INT,
    DataUltimaModifica    DATETIME2(3),
    IsDeleted             BIT
);

INSERT INTO A_Persone (PersonID, LastName, FirstName, Email, City, AziendaInCuiLavora_ID, DataUltimaModifica, IsDeleted)
VALUES
    (1, 'Rossi', 'Mario', 'mario@rossi.it', 'Milano', 1, '1 apr 2016 15:01:02:129', 0),
    (2, 'Verdi', 'Matteo', 'matteo@verdi.it', 'Milano', 2, '1 apr 2016 15:01:02:129', 0),
    (3, 'Gialli', 'Notorio', 'notorio@gialli.it', 'Milano', 6, '1 apr 2016 15:01:02:129', 0),
    (4, 'Bianchi', 'Diego', 'diego@bianchi.it', 'Milano', 7, '1 apr 2016 15:01:02:129', 0);

--
-- Anagrafica semplice
--
CREATE TABLE A_Aziende (
    AziendaID INT,
    RagioneSociale VARCHAR(255),
    Email VARCHAR(255),
    City VARCHAR(255),
    ContattoPrincipale INT,
    DataInizioAttivita DATE,
    DataInvioCedolini DATETIME,
    DataUltimaModifica DATETIME2(3),
    IsDeleted BIT
);

INSERT INTO A_Aziende (AziendaID, RagioneSociale, Email, City, ContattoPrincipale, DataInizioAttivita, DataInvioCedolini, DataUltimaModifica, IsDeleted)
VALUES
    (1, 'Mulino''Bianco', 'mario@mulino.it', 'Milano2', 1, '2001-11-11', '2021-05-05 16:10:23', '1 apr 2016 15:01:02:129', 0),
    (2, 'MulinoVerde', 'colore@verde.it', 'Napoli', 2, '2003-02-02', '2021-07-07 10:11:03',  '1 apr 2016 15:01:02:179', 0),
    (3, 'Cantore''Verde', 'cantore@verde.it', 'Palermo', 5, '2003-02-02', '2021-04-07 10:11:03',  '1 apr 2016 15:01:02:292', 0);

--
-- Gestione Prodotti/Listini
--
CREATE TABLE B_Prodotti (
                            ProdottoID INT,
                            Descrizione VARCHAR(255),
                            Prezzo MONEY
)

    INSERT INTO B_Prodotti (ProdottoID, Descrizione, Prezzo) VALUES (1, 'Zucchero', 10.5), (2, 'Sale', 12.5)

CREATE TABLE B_Listini (
                           ListinoID INT,
                           Nome VARCHAR(255),
                           Descrizione VARCHAR(255) DEFAULT NULL
);

INSERT INTO B_Listini (ListinoID, Nome) VALUES (1, 'Nuovi Clienti'), (2, 'Clienti Fedeli');

CREATE TABLE B_ListiniProdotti (
                                   Chiave INT,
                                   ListinoID INT,
                                   ProdottoID INT,
                                   Prezzo MONEY,
                                   Disattivato BIT DEFAULT 0
);

INSERT INTO B_ListiniProdotti (Chiave, ListinoID, ProdottoID, Prezzo) VALUES (100, 1, 1, 10), (101, 2, 1, 9);

CREATE TABLE C_Aliquote (
                            AliquotaID INT,
                            Descrizione VARCHAR(255),
                            Percentuale INT
);

INSERT INTO C_Aliquote (AliquotaID, Descrizione, Percentuale) VALUES
                                                                  (1, 'Aliquota Base', 22),
                                                                  (2, 'Aliquita Ridotta', 10),
                                                                  (3, 'Non Tassabile', 0);

CREATE TABLE C_Fatture (
                           FatturaID INT,
                           ClienteID INT,
                           Totale MONEY,
                           DataAgg DATETIME
);

INSERT INTO C_Fatture (FatturaID, ClienteID, Totale, DataAgg) VALUES
                                                                  (1, 1, 10, '2021-07-07 10:11:03'),
                                                                  (2, 1, 9, '2021-05-07 15:19:13'),
                                                                  (3, 1, 9, '2021-03-07 18:12:43');

CREATE TABLE C_FattureDettagli (
                                   FatturaID INT,
                                   ProdottoID INT,
                                   AliquotaID INT,
                                   Prezzo MONEY,
                                   Quantita INT,
                                   Subtotale MONEY
);

INSERT INTO C_FattureDettagli (FatturaID, ProdottoID, AliquotaID, Prezzo, Quantita, Subtotale) VALUES
                                                                                                   (1, 1, 1, 10, 1, 10),
                                                                                                   (1, 2, 2, 12, 1, 12),
                                                                                                   (1, 2, 3, 9, 1, 9),
                                                                                                   (2, 1, 1, 10, 1, 10),
                                                                                                   (2, 1, 2, 9, 1, 9),
                                                                                                   (2, 2, 3, 9, 1, 9),
                                                                                                   (3, 1, 1, 10, 1, 10),
                                                                                                   (3, 2, 2, 9, 1, 9),
                                                                                                   (3, 2, 3, 9, 1, 9);

CREATE TABLE D_Sedi (
                        IdSede INT,
                        NomeSede VARCHAR(100),
                        CittaSede VARCHAR(100),
                        Affitto MONEY,
                        DataAgg DATETIME
);

INSERT INTO D_Sedi (IdSede, NomeSede, CittaSede, Affitto, DataAgg) VALUES
                                                                       (1, 'Sede Rossa', 'Palermo', 10, '2021-01-01'),
                                                                       (2, 'Sede Verde', 'Milano', 12, '2021-01-01'),
                                                                       (3, 'Sede Bianca', 'Bologna', 9, '2021-01-01'),
                                                                       (4, 'Sede Blu', 'Torino', 10, '2021-01-01');
