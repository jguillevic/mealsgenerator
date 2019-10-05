DROP DATABASE IF EXISTS meals_generator;

CREATE DATABASE meals_generator;

USE meals_generator;

CREATE TABLE User
(
	Id INT NOT NULL AUTO_INCREMENT
	, Login NVARCHAR(200) NOT NULL UNIQUE
	, Email NVARCHAR(200) NOT NULL UNIQUE
	, RememberMe TINYINT(1) NOT NULL
	, PasswordHash NVARCHAR(128) NOT NULL -- SHA-512
	, PRIMARY KEY (Id)
 ) ENGINE=InnoDB;

 CREATE TABLE FacebookUser
(
	FacebookId BIGINT NOT NULL UNIQUE
	, FirstName NVARCHAR(200) NOT NULL
	, LastName NVARCHAR(200) NOT NULL
	, Email NVARCHAR(200) NOT NULL
	, Birthday DATE NOT NULL
	, ProfilePictureUrl NVARCHAR(500) NOT NULL
	, AccessToken NVARCHAR(500) NOT NULL
	, PRIMARY KEY (FacebookId)
 ) ENGINE=InnoDB;

CREATE TABLE ShoppingCategory
(
	Id INT NOT NULL AUTO_INCREMENT
	, Code NVARCHAR(20) NOT NULL
	, Name NVARCHAR(200) NOT NULL
	, PRIMARY KEY (Id)
) ENGINE=InnoDB;

CREATE TABLE UnitCategory
(
	Id INT NOT NULL AUTO_INCREMENT
	, Code NVARCHAR(20) NOT NULL
	, PRIMARY KEY (Id)
) ENGINE=InnoDB;

CREATE TABLE Unit
(
	Id INT NOT NULL AUTO_INCREMENT
	, Name NVARCHAR(200) NOT NULL
	, Code NVARCHAR(20) NOT NULL
	, ConversionFactor FLOAT NULL
	, CategoryId INT NOT NULL
	, PRIMARY KEY (Id)
	, FOREIGN KEY (CategoryId) REFERENCES UnitCategory(Id)
) ENGINE=InnoDB;

CREATE TABLE Ingredient
(
	Id INT NOT NULL AUTO_INCREMENT
	, Name NVARCHAR(200) NOT NULL
	, DefaultUnitId INT NOT NULL
	, PRIMARY KEY (Id)
	, FOREIGN KEY (DefaultUnitId) REFERENCES Unit(Id)
) ENGINE=InnoDB;

CREATE TABLE Recipe
(
	Id INT NOT NULL AUTO_INCREMENT
	, Name NVARCHAR(200) NOT NULL
	, DefaultPersonNumber INT NOT NULL
	, PreparationTime INT NOT NULL
	, CookingTime INT NOT NULL
	, PRIMARY KEY (Id)
) ENGINE=InnoDB;

CREATE TABLE Instruction
(
	Id INT NOT NULL AUTO_INCREMENT
	, RecipeId INT NOT NULL
	, Content TEXT NOT NULL
	, `Order` INT NOT NULL
	, PRIMARY KEY (Id)
	, FOREIGN KEY (RecipeId) REFERENCES Recipe(Id)
) ENGINE=InnoDB;

CREATE TABLE Recipe_Ingredient
(
	RecipeId INT NOT NULL
	, IngredientId INT NOT NULL
	, Quantity FLOAT NOT NULL
	, UnitId INT NOT NULL
	, PRIMARY KEY (RecipeId, IngredientId)
	, FOREIGN KEY (RecipeId) REFERENCES Recipe(Id)
	, FOREIGN KEY (IngredientId) REFERENCES Ingredient(Id)
	, FOREIGN KEY (UnitId) REFERENCES Unit(Id)
) ENGINE=InnoDB;

CREATE TABLE MealKind
(
	Id INT NOT NULL AUTO_INCREMENT
	, Code NVARCHAR(20) NOT NULL
	, Name NVARCHAR(200) NOT NULL
	, PRIMARY KEY (Id)
) ENGINE=InnoDB;

CREATE TABLE MealItem
(
	Id INT NOT NULL AUTO_INCREMENT
	, Name NVARCHAR(200) NOT NULL
	, WeekProposedMaxCount INT NOT NULL
	, RecipeId INT NULL
	, PRIMARY KEY (Id)
) ENGINE=InnoDB;

CREATE TABLE Meal
(
	Id INT NOT NULL AUTO_INCREMENT
	, PRIMARY KEY (Id)
) ENGINE=InnoDB;

CREATE TABLE Meal_MealKind
(
	MealId INT NOT NULL
	, MealKindId INT NOT NULL
	, PRIMARY KEY (MealId, MealKindId)
) ENGINE=InnoDB;

CREATE TABLE Meal_MealItem
(
	MealId INT NOT NULL 
	, MealItemId INT NOT NULL
	, PRIMARY KEY (MealId, MealItemId)
) ENGINE=InnoDB;

CREATE TABLE PlannifiedMeal
(
	Id INT NOT NULL AUTO_INCREMENT
	, Date DATE NOT NULL
	, PersonNumber INT NOT NULL
	, KindId INT NOT NULL
	, MealId INT NOT NULL
	, PRIMARY KEY (Id)
	, FOREIGN KEY (KindId) REFERENCES MealKind(Id)
	, FOREIGN KEY (MealId) REFERENCES Meal(Id)
) ENGINE=InnoDB;

CREATE TABLE ShoppingList
(
	Id INT NOT NULL AUTO_INCREMENT
	, Name NVARCHAR(200) NOT NULL
	, PRIMARY KEY (Id)
) ENGINE=InnoDB;

CREATE TABLE ShoppingListItem
(
	Id INT NOT NULL AUTO_INCREMENT
	, ShoppingListId INT NOT NULL
	, Content TEXT NOT NULL
	, IsHandled TINYINT(1) NOT NULL
	, PRIMARY KEY (Id)
	, FOREIGN KEY (ShoppingListId) REFERENCES ShoppingList(Id)
) ENGINE=InnoDB;