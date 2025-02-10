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
            "minimal_packaging": 0,
            "second_hand": 0,
            "environmental_impact": 0,
            "mindful_consumption": 0,
            "sustainable_brands": 0
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

-- Inserting a default user, "John Doe"
INSERT INTO users (email, username, password, lvl, points) 
VALUES (
    'johndoe@gmail.com',
    'johndoe',
    'johndoepassword',
    1,
    0
);
