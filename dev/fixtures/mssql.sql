
USE MSSQL;

--
-- Anagrafica semplice
--
CREATE TABLE A_Persone (
    PersonID INT,
    LastName VARCHAR(255),
    FirstName VARCHAR(255),
    Email VARCHAR(255),
    City VARCHAR(255),
    IsDeleted BIT
);

INSERT INTO A_Persone (PersonID, LastName, FirstName, Email, City, IsDeleted) VALUES (1, 'Rossi', 'Mario', 'mario@rossi.it', 'Milano', 0)

--
-- Anagrafica semplice
--
CREATE TABLE A_Aziende (
       AziendaID INT,
       RagioneSociale VARCHAR(255),
       Email VARCHAR(255),
       City VARCHAR(255),
       DataInizioAttivita DATE,
       DataInvioCedolini DATETIME,
       DataUltimaModifica DATETIME,
       IsDeleted BIT
);

INSERT INTO A_Aziende (AziendaID, RagioneSociale, Email, City, DataInizioAttivita, DataInvioCedolini, DataUltimaModifica, IsDeleted)
    VALUES
       (1, 'MulinoBianco', 'mario@mulino.it', 'Milano', '2001-11-11', '2021-05-05 16:10:23', '2021-01-01 12:00:00', 0),
       (2, 'MulinoVerde', 'colore@verde.it', 'Napoli', '2003-02-02', '2021-07-07 10:11:03',  '2021-01-02 12:00:00', 0);

--
-- Gestione Prodotti/Listini
--
CREATE TABLE B_Prodotti (
    ProdottoID INT,
    Descrizione VARCHAR(255),
    Prezzo MONEY
)

INSERT INTO B_Prodotti (ProdottoID, Descrizione, Prezzo) VALUES (1, 'Zucchero', 10.5)

CREATE TABLE B_Listini (
    ListinoID INT,
    Nome VARCHAR(255),
)

INSERT INTO B_Listini (ListinoID, Nome) VALUES (1, 'Nuovi Clienti'), (2, 'Clienti Fedeli')

CREATE TABLE B_ListiniProdotti (
    ListinoID INT,
    ProdottoID INT,
    Prezzo MONEY
)

INSERT INTO B_ListiniProdotti (ListinoID, ProdottoID, Prezzo) VALUES (1, 1, 10), (2, 1, 9)
