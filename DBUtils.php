<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

class DBConnection 
{
    private $host = '127.0.0.1';
    private $db   = 'hotels';
    private $user = 'root';
    private $pass = '';
    private $charset = 'utf8';

    private $pdo;
    private $error;

    public function __construct() 
    {
        $dsn = "mysql:host=127.0.0.1;port=3307;dbname=$this->db;charset=$this->charset";

        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false
        ];

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $opt);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            error_log("Error connecting to DB: " . $this->error);
            throw new Exception("Database connection failed.");
        }
    }

    // Fetch filtered hotels with pagination
    public function getFilteredHotels($nameFilter = '', $addressFilter = '', $start = 0, $perPage = 10) 
    {
        $query = "SELECT * FROM hotels WHERE 1 = 1";
        $params = [];

        if (!empty($nameFilter)) {
            $query .= " AND name LIKE ?";
            $params[] = '%' . $nameFilter . '%';
        }

        if (!empty($addressFilter)) {
            $query .= " AND address LIKE ?";
            $params[] = '%' . $addressFilter . '%';
        }

        $query .= " LIMIT ?, ?";
        $params[] = (int)$start;
        $params[] = (int)$perPage;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get total count of hotels with filters
    public function getHotelCount($nameFilter = '', $addressFilter = '') 
    {
        $query = "SELECT COUNT(*) AS total FROM hotels WHERE 1 = 1";
        $params = [];

        if (!empty($nameFilter)) {
            $query .= " AND name LIKE ?";
            $params[] = '%' . $nameFilter . '%';
        }

        if (!empty($addressFilter)) {
            $query .= " AND address LIKE ?";
            $params[] = '%' . $addressFilter . '%';
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // Insert a new user
    public function insertUser($username, $email, $password) {
        $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $email, $password]);
    }

    // Fetch hotel by name
    public function getHotelByName($hotel) {
        if (empty($hotel)) {
            return [];
        }
        $stmt = $this->pdo->prepare("SELECT * FROM hotels WHERE name = ?");
        $stmt->execute([$hotel]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch hotel by ID
    public function getHotelById($id) {
        if (empty($id)) {
            return [];
        }
        $stmt = $this->pdo->prepare("SELECT * FROM hotels WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch all hotels
    public function getAllHotels() {
        $stmt = $this->pdo->prepare("SELECT * FROM hotels");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch all rooms with hotel details
    public function getAllRooms() {
        $stmt = $this->pdo->prepare("SELECT rooms.id, hotels.name AS hotel_name, rooms.room_number, rooms.category, rooms.price
                                     FROM rooms
                                     INNER JOIN hotels ON rooms.hotel_id = hotels.id");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Insert a new hotel
    public function insertHotel($name, $address) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO hotels (name, address) VALUES (:name, :address)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':address', $address);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Insert failed: " . $e->getMessage());
        }
    }
    // Insert a new room
    public function insertRoom($hotel_id, $room_number, $category, $price) {
        $stmt = $this->pdo->prepare("INSERT INTO rooms (hotel_id, room_number, category, price) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$hotel_id, $room_number, $category, $price]);
    }

    // Fetch room by type
    public function getRoomByType($roomType) {
        $stmt = $this->pdo->prepare("SELECT * FROM rooms WHERE category = ? LIMIT 1");
        $stmt->execute([$roomType]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertAdminReservation($admin_id, $room_id, $start_date, $end_date) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO reservations (admin_id, room_id, start_date, end_date)
                VALUES (?, ?, ?, ?)
            ");
            return $stmt->execute([$admin_id, $room_id, $start_date, $end_date]);
        } catch (PDOException $e) {
            error_log("Error inserting admin reservation: " . $e->getMessage());
            return false;
        }
    }
    
    public function insertUserReservation($user_id, $room_id, $start_date, $end_date) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO reservations (adminid, roomid, startdate, enddate)
                VALUES (?, ?, ?, ?)
            ");
            return $stmt->execute([$user_id, $room_id, $start_date, $end_date]);
        } catch (PDOException $e) {
            error_log("Error inserting reservation: " . $e->getMessage());
            return false;
        }
    }
    
    
    public function getAllReservations() {
        try {
            $stmt = $this->pdo->prepare("
                SELECT reservations.id, rooms.room_number, hotels.name AS hotel_name, 
                       reservations.start_date, reservations.end_date
                FROM reservations
                JOIN rooms ON reservations.room_id = rooms.id
                JOIN hotels ON rooms.hotel_id = hotels.id
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching reservations: " . $e->getMessage());
            return [];
        }
    }
    

    // Fetch reservations by user ID
    public function getReservationByUserId($userId) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    reservations.id,
                    rooms.room_number,
                    hotels.name AS hotel_name,
                    reservations.start_date,
                    reservations.end_date
                FROM 
                    reservations
                JOIN 
                    rooms ON reservations.room_id = rooms.id
                JOIN 
                    hotels ON rooms.hotel_id = hotels.id
                WHERE 
                    reservations.user_id = ?
            ");
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching reservations: " . $e->getMessage());
            return [];
        }
    }

    // Delete a hotel by ID
    public function deleteHotel($id) {
        $stmt = $this->pdo->prepare("DELETE FROM hotels WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Delete a reservation by ID
    public function deleteReservation($id) {
        $stmt = $this->pdo->prepare("DELETE FROM reservations WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Fetch user by username
    public function selectUserByUsername($username) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch admin by username
    public function selectAdminByUsername($username) {
        $stmt = $this->pdo->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch rooms by hotel ID
    public function getRoomsByHotelId($hotelId) {
        if (empty($hotelId)) {
            return [];
        }
        $stmt = $this->pdo->prepare("
            SELECT rooms.id, rooms.room_number, rooms.category, rooms.price 
            FROM rooms 
            WHERE rooms.hotel_id = ?
        ");
        $stmt->execute([$hotelId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch room by type and hotel ID
    public function getRoomByTypeAndHotel($roomType, $hotelId) {
        $stmt = $this->pdo->prepare("SELECT * FROM rooms WHERE category = ? AND hotel_id = ? LIMIT 1");
        $stmt->execute([$roomType, $hotelId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function updateReservation($id, $start_date, $end_date) {
        try {
            $stmt = $this->pdo->prepare(
                "UPDATE reservations 
                 SET start_date = ?, end_date = ? 
                 WHERE id = ?"
            );
            return $stmt->execute([$start_date, $end_date, $id]);
        } catch (PDOException $e) {
            error_log("Error updating reservation: " . $e->getMessage());
            return false;
        }
    }
    
    

    // Output data as JSON
    public function show($value) {
        echo json_encode($value);
    }

    // API Runner for GET requests
    public function run() {
        if (isset($_GET['action']) && !empty($_GET['action'])) {
            switch ($_GET['action']) {
                case 'getFilteredHotels':
                    $nameFilter = $_GET['nameFilter'] ?? '';
                    $addressFilter = $_GET['addressFilter'] ?? '';
                    $start = $_GET['start'] ?? 0;
                    $perPage = $_GET['perPage'] ?? 10;
                    $result = $this->getFilteredHotels($nameFilter, $addressFilter, $start, $perPage);
                    $this->show($result);
                    break;

                case 'getHotelCount':
                    $nameFilter = $_GET['nameFilter'] ?? '';
                    $addressFilter = $_GET['addressFilter'] ?? '';
                    $result = $this->getHotelCount($nameFilter, $addressFilter);
                    $this->show(['total' => $result]);
                    break;

                    case 'insertAdminReservation':
                        $admin_id   = $_GET['admin_id']   ?? '';
                        $room_id    = $_GET['room_id']    ?? '';
                        $start_date = $_GET['start_date'] ?? '';
                        $end_date   = $_GET['end_date']   ?? '';
                        
                        $result = $this->insertAdminReservation($admin_id, $room_id, $start_date, $end_date);
                        // Return 1 on success, 0 on fail
                        $this->show($result ? 1 : 0);
                        exit;
                    
                    case 'insertUserReservation':
                        $user_id    = $_GET['user_id']    ?? '';
                        $room_id    = $_GET['room_id']    ?? '';
                        $start_date = $_GET['start_date'] ?? '';
                        $end_date   = $_GET['end_date']   ?? '';
                    
                        $result = $this->insertUserReservation($user_id, $room_id, $start_date, $end_date);
                        $this->show($result ? 1 : 0);
                        exit;
                    
                        case 'selectAdminByUsername':
                            $username = $_GET['username'] ?? '';
                            if (empty($username)) {
                                // Return empty if no username provided
                                $this->show([]);
                                return;
                            }
                            $admin = $this->selectAdminByUsername($username); 
                            $this->show($admin); 
                            break;

                            case 'editReservation':
                                $id = $_GET['id'] ?? 0;
                                $start_date = $_GET['start_date'] ?? '';
                                $end_date   = $_GET['end_date']   ?? '';
                            
                                // Do your update logic here:
                                $result = $this->updateReservation($id, $start_date, $end_date); 
                                // Return 1 if success, 0 if failure
                                $this->show($result ? 1 : 0);
                                exit;
                            

                            case 'deleteReservation':
                                $id = $_GET['id'] ?? 0;
                                $result = $this->deleteReservation($id); // your existing method
                                // Return JSON: 1 if success, 0 if failure
                                $this->show($result ? 1 : 0);
                                exit;
                            
                          
// --------------------------------------------case 'selectIdByUsername':
    $username = $_GET['username'] ?? '';
    if (empty($username)) {
        // Return empty array if username is missing
        $this->show([]);
    } else {
        $user = $this->selectUserByUsername($username);
        $this->show($user);
    }
    break;
                // Add additional cases as needed
            }
        }
    }
}

// Run the API handler
$conn = new DBConnection();
$conn->run();