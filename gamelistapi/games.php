<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Authorization");

include_once "db.php";

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Fetch all games
        $sql = "SELECT * FROM games";
        $result = $conn->query($sql);
        $games = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $games[] = $row;
            }
        }
        echo json_encode($games);
        break;

    case 'POST':
        // Add a new game
        $data = json_decode(file_get_contents("php://input"), true);
        $title = $data['title'];
        $genre = $data['genre'];
        $release_year = $data['release_year'];

        $sql = "INSERT INTO games (title, genre, release_year) VALUES ('$title', '$genre', $release_year)";
        if ($conn->query($sql)) {
            echo json_encode(["message" => "Game added successfully"]);
        } else {
            echo json_encode(["message" => "Error adding game: " . $conn->error]);
        }
        break;

    case 'DELETE':
        // Delete a game
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'];

        $sql = "DELETE FROM games WHERE id=$id";
        if ($conn->query($sql)) {
            echo json_encode(["message" => "Game deleted successfully"]);
        } else {
            echo json_encode(["message" => "Error deleting game: " . $conn->error]);
        }
        break;

    case 'PUT':
        // Update a game
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'];
        $title = $data['title'];
        $genre = $data['genre'];
        $release_year = $data['release_year'];

        $sql = "UPDATE games SET title='$title', genre='$genre', release_year=$release_year WHERE id=$id";
        if ($conn->query($sql)) {
            echo json_encode(["message" => "Game updated successfully"]);
        } else {
            echo json_encode(["message" => "Error updating game: " . $conn->error]);
        }
        break;
}
$conn->close();
?>
