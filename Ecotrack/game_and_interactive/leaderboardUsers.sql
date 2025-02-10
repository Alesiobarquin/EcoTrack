DROP TABLE IF EXISTS `users`;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    lvl int(10) unsigned NOT NULL,
    points int(10) unsigned NOT NULL,
    badges JSON DEFAULT '[]',
    completed_challenges JSON DEFAULT '[]',
    category_scores JSON DEFAULT '{
        "purchasing": {
            "public_transportation": 0,
            "fuel_efficiency": 0,
            "carpooling": 0,
            "active_transport": 0,
            "electric_vehicles": 0
        },
        "energy": {
            "efficient_appliances": 0,
            "power_management": 0,
            "energy_monitoring": 0,
            "renewable_energy": 0,
            "temperature_control": 0
        },
        "waste": {
            "recycling_habits": 0,
            "reusable_items": 0,
            "plastic_reduction": 0,
            "composting": 0,
            "hazardous_waste": 0
        },
        "transportation": {
            "public_transportation": 0,
            "fuel_efficiency": 0,
            "carpooling": 0,
            "active_transport": 0,
            "electric_vehicles": 0
        },
        "diet": {
            "plant_based_diet": 0,
            "local_food": 0,
            "food_waste": 0,
            "home_growing": 0,
            "animal_products": 0
        }
    }'
);

-- Example of inserting a default user, "John Doe"
INSERT INTO users (email, username, password, lvl, points)
VALUES 
    ('test1@gmail.com', 'first', '1234', 2, 210),
    ('test9@gmail.com', 'ninth', '1234', 1, 130),
    ('test2@gmail.com', 'second', '1234', 2, 200),
    ('test3@gmail.com', 'third', '1234', 1, 190),
    ('test5@gmail.com', 'fifth', '1234', 1, 170),
    ('test4@gmail.com', 'fourth', '1234', 1, 180),
    ('test7@gmail.com', 'seventh', '1234', 1, 150),
    ('test8@gmail.com', 'eighth', '1234', 1, 140),
    ('test6@gmail.com', 'sixth', '1234', 1, 160),
    ('test10@gmail.com', 'tenth', '1234', 1, 120);

