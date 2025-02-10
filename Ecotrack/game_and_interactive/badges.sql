DROP TABLE IF EXISTS `badges`;

-- Table for badges
CREATE TABLE badges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    description TEXT,
    points_required INT,
    image_url VARCHAR(255) 
);

INSERT INTO badges (name, description, points_required, image_url)
VALUES('Log In Badge', 'Badge earned for making an account', 0, '../resources/badgeicons/signedupBadge.png'),
('Starting out', 'Reach Level 1', 100, '../resources/badgeicons/meh.png'),
('Getting Somewhere', 'Reach Level 10', 1000, '../resources/badgeicons/circle.png'),
('Small Contributions', 'Reach Level 25', 2500, '../resources/badgeicons/star.png'),
('Big Changes', 'Reach Level 50', 50000, '../resources/badgeicons/orb.png')
