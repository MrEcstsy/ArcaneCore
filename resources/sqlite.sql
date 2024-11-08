-- #!sqlite
-- # { players
-- #  { initialize
CREATE TABLE IF NOT EXISTS players (
    uuid VARCHAR(36) PRIMARY KEY,
    username VARCHAR(16),
    gems INT DEFAULT 0,
    kills INT DEFAULT 0,
    deaths INT DEFAULT 0,
    bounty INT DEFAULT 0,
    plevel INT DEFAULT 0
    );
-- # }

-- #  { select
SELECT *
FROM players;
-- #  }

-- #  { create
-- #      :uuid string
-- #      :username string
-- #      :gems int
-- #      :kills int
-- #      :deaths int
-- #      :bounty int
-- #      :plevel int
INSERT OR REPLACE INTO players(uuid, username, gems, kills, deaths, bounty, plevel)
VALUES (:uuid, :username, :gems, :kills, :deaths, :bounty, :plevel);
-- #  }

-- #  { update
-- #      :uuid string
-- #      :username string
-- #      :gems int
-- #      :kills int
-- #      :deaths int
-- #      :bounty int
-- #      :plevel int
UPDATE players
SET username=:username,
    gems=:gems,
    kills=:kills,
    deaths=:deaths,
    bounty=:bounty,
    plevel=:plevel
WHERE uuid=:uuid;
-- #  }

-- #  { delete
-- #      :uuid string
DELETE FROM players
WHERE uuid=:uuid;
-- #  }

-- # { homes
-- #  { initialize
CREATE TABLE IF NOT EXISTS homes (
    uuid VARCHAR(36),
    home_name VARCHAR(32),
    world_name VARCHAR(32),
    x INT,
    y INT,
    z INT,
    max_homes INT DEFAULT 3,
    PRIMARY KEY (uuid, home_name)
    );
-- #  }
-- # { select
SELECT *
FROM homes;
-- # }

-- #  { create
-- #      :uuid string
-- #      :home_name string
-- #      :world_name string
-- #      :x int
-- #      :y int
-- #      :z int
-- #      :max_homes int
INSERT OR REPLACE INTO homes(uuid, home_name, world_name, x, y, z, max_homes)
VALUES (:uuid, :home_name, :world_name, :x, :y, :z, :max_homes);
-- #  }

-- #  { delete
-- #      :uuid string
-- #      :home_name string
DELETE FROM homes
WHERE uuid = :uuid AND home_name = :home_name;
-- #  }

-- #  { update
-- #      :uuid string
-- #      :max_homes int
UPDATE homes
SET max_homes = :max_homes
WHERE uuid = :uuid;
-- #   }
-- #  }
-- # }