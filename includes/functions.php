<?php
require_once 'db.php';

function createApplicant($data)
{
    global $db;
    try {
        $sql = "INSERT INTO applicants (firstName, lastName, email, phone, yearsOfExperience, specialization, favoriteDBMS, favoriteFrontendFramework) 
                VALUES (:firstName, :lastName, :email, :phone, :yearsOfExperience, :specialization, :favoriteDBMS, :favoriteFrontendFramework)";
        $stmt = $db->prepare($sql);
        $stmt->execute($data);

        return [
            "message" => "Applicant created successfully.",
            "statusCode" => 200
        ];
    } catch (PDOException $e) {
        return [
            "message" => "Failed to create applicant: " . $e->getMessage(),
            "statusCode" => 400
        ];
    }
}

function readApplicants($search = '') {
    global $db;
    try {
        // Ensure the search term is not empty and avoid SQL injection
        $searchTerm = '%' . $search . '%';

        // Query to search only by first name or last name
        $query = "SELECT * FROM applicants 
                  WHERE firstName LIKE :search 
                  OR lastName LIKE :search";

        $stmt = $db->prepare($query);
        $stmt->execute([':search' => $searchTerm]);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            "message" => "Query executed successfully.",
            "statusCode" => 200,
            "querySet" => $result
        ];
    } catch (PDOException $e) {
        return [
            "message" => "Failed to fetch applicants: " . $e->getMessage(),
            "statusCode" => 400
        ];
    }
}

function updateApplicant($id, $data) {
    global $db;

    try {
        $sql = "UPDATE applicants 
                SET firstName = :firstName, 
                    lastName = :lastName, 
                    email = :email, 
                    phone = :phone, 
                    yearsOfExperience = :yearsOfExperience, 
                    specialization = :specialization, 
                    favoriteDBMS = :favoriteDBMS, 
                    favoriteFrontendFramework = :favoriteFrontendFramework
                WHERE id = :id";

        $stmt = $db->prepare($sql);

        // Add `:id` to the data array
        $data[':id'] = $id;

        // Execute the query
        if ($stmt->execute($data)) {
            return [
                'message' => 'Applicant updated successfully.',
                'statusCode' => 200
            ];
        } else {
            return [
                'message' => 'Failed to update applicant.',
                'statusCode' => 400
            ];
        }
    } catch (PDOException $e) {
        return [
            'message' => 'Database error: ' . $e->getMessage(),
            'statusCode' => 400
        ];
    }
}


function deleteApplicant($id) {
    global $db;

    try {
        $sql = "DELETE FROM applicants WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return [
                'message' => 'Applicant deleted successfully.',
                'statusCode' => 200
            ];
        } else {
            return [
                'message' => 'Failed to delete applicant.',
                'statusCode' => 400
            ];
        }
    } catch (PDOException $e) {
        return [
            'message' => 'Database error: ' . $e->getMessage(),
            'statusCode' => 400
        ];
    }
}


function getApplicantById($id) {
    global $db;

    try {
        $stmt = $db->prepare("SELECT * FROM applicants WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return [
                'message' => 'Applicant found successfully.',
                'statusCode' => 200,
                'querySet' => $result
            ];
        } else {
            return [
                'message' => 'Applicant not found.',
                'statusCode' => 400
            ];
        }
    } catch (PDOException $e) {
        return [
            'message' => 'Database error: ' . $e->getMessage(),
            'statusCode' => 400
        ];
    }
}


function getAllApplicants($searchTerm = '') {
    global $db;

    try {
        // Ensure that the search term is properly handled
        $searchTerm = '%' . strtolower($searchTerm) . '%';  // To make the search case-insensitive

        // SQL query to filter applicants by first name or last name
        $sql = "SELECT * FROM applicants 
                WHERE LOWER(firstName) LIKE :searchTerm 
                OR LOWER(lastName) LIKE :searchTerm 
                ORDER BY id DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute([':searchTerm' => $searchTerm]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'message' => 'Applicants retrieved successfully.',
            'statusCode' => 200,
            'querySet' => $result
        ];
    } catch (PDOException $e) {
        return [
            'message' => 'Database error: ' . $e->getMessage(),
            'statusCode' => 400
        ];
    }
}

function logActivity($userId, $type) {
    global $db;

    try {
        $stmt = $db->prepare("
            INSERT INTO activity_logs_tbl (user_id, type, createDate)
            VALUES (:user_id, :type, NOW())
        ");
        $stmt->execute([
            ':user_id' => $userId,
            ':type' => $type
        ]);
    } catch (PDOException $e) {
        // Optionally handle the error or log it
        error_log("Error logging activity: " . $e->getMessage());
    }
}


