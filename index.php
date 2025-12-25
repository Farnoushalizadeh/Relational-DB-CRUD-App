<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("DB.php");

function sanitize_input($data) {
    $data = trim($data);
    $data = htmlspecialchars($data);
    return $data;
}

$action = isset($_GET['action']) ? sanitize_input($_GET['action']) : 'view_all_students';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['form_type'])) {
        $form_type = sanitize_input($_POST['form_type']);

        switch ($form_type) {
            case 'create_student':
                $name = sanitize_input($_POST['name']);
                $surname = sanitize_input($_POST['surname']);
                $email = sanitize_input($_POST['email']);

                $sql = "INSERT INTO Student (name, surname, email) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "sss", $name, $surname, $email);
                    if (mysqli_stmt_execute($stmt)) {
                        $message = "<p style='color:green;'>Student '{$name} {$surname}' added successfully!</p>";
                    } else {
                        $message = "<p style='color:red;'>Error adding student: " . mysqli_error($conn) . "</p>";
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $message = "<p style='color:red;'>Error preparing statement: " . mysqli_error($conn) . "</p>";
                }
                $action = 'view_all_students';
                break;

            case 'update_student':
                $student_id = sanitize_input($_POST['student_id']);
                $name = sanitize_input($_POST['name']);
                $surname = sanitize_input($_POST['surname']);
                $email = sanitize_input($_POST['email']);

                if (!is_numeric($student_id)) {
                    $message = "<p style='color:red;'>Invalid Student ID for update.</p>";
                    break;
                }

                $sql = "UPDATE Student SET name = ?, surname = ?, email = ? WHERE student_id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "sssi", $name, $surname, $email, $student_id);
                    if (mysqli_stmt_execute($stmt)) {
                        $message = "<p style='color:green;'>Student ID {$student_id} updated successfully!</p>";
                    } else {
                        $message = "<p style='color:red;'>Error updating student: " . mysqli_error($conn) . "</p>";
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $message = "<p style='color:red;'>Error preparing statement: " . mysqli_error($conn) . "</p>";
                }
                $action = 'view_all_students';
                break;

            case 'delete_student':
                $student_id = sanitize_input($_POST['student_id']);

                if (!is_numeric($student_id)) {
                    $message = "<p style='color:red;'>Invalid Student ID for deletion.</p>";
                    break;
                }

                $sql = "DELETE FROM Student WHERE student_id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "i", $student_id);
                    if (mysqli_stmt_execute($stmt)) {
                        if (mysqli_stmt_affected_rows($stmt) > 0) {
                             $message = "<p style='color:green;'>Student ID {$student_id} deleted successfully!</p>";
                        } else {
                             $message = "<p style='color:blue;'>No student found with ID {$student_id} to delete.</p>";
                        }
                    } else {
                        $message = "<p style='color:red;'>Error deleting student: " . mysqli_error($conn) . "</p>";
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $message = "<p style='color:red;'>Error preparing statement: " . mysqli_error($conn) . "</p>";
                }
                $action = 'view_all_students';
                break;

            case 'create_department':
                $department_name = sanitize_input($_POST['department_name']);
                $department_head = sanitize_input($_POST['department_head']);
                $location = sanitize_input($_POST['location']);

                $sql = "INSERT INTO Department (department_name, department_head, location) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "sss", $department_name, $department_head, $location);
                    if (mysqli_stmt_execute($stmt)) {
                        $message = "<p style='color:green;'>Department '{$department_name}' added successfully!</p>";
                    } else {
                        $message = "<p style='color:red;'>Error adding department: " . mysqli_error($conn) . "</p>";
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $message = "<p style='color:red;'>Error preparing statement: " . mysqli_error($conn) . "</p>";
                }
                $action = 'view_all_departments';
                break;

            case 'update_department':
                $department_id = sanitize_input($_POST['department_id']);
                $department_name = sanitize_input($_POST['department_name']);
                $department_head = sanitize_input($_POST['department_head']);
                $location = sanitize_input($_POST['location']);

                if (!is_numeric($department_id)) {
                    $message = "<p style='color:red;'>Invalid Department ID for update.</p>";
                    break;
                }

                $sql = "UPDATE Department SET department_name = ?, department_head = ?, location = ? WHERE department_id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "sssi", $department_name, $department_head, $location, $department_id);
                    if (mysqli_stmt_execute($stmt)) {
                        $message = "<p style='color:green;'>Department ID {$department_id} updated successfully!</p>";
                    } else {
                        $message = "<p style='color:red;'>Error updating department: " . mysqli_error($conn) . "</p>";
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $message = "<p style='color:red;'>Error preparing statement: " . mysqli_error($conn) . "</p>";
                }
                $action = 'view_all_departments';
                break;

            case 'delete_department':
                $department_id = sanitize_input($_POST['department_id']);
                if (!is_numeric($department_id)) {
                    $message = "<p style='color:red;'>Invalid Department ID for deletion.</p>";
                    break;
                }
                $sql = "DELETE FROM Department WHERE department_id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "i", $department_id);
                    if (mysqli_stmt_execute($stmt)) {
                        if (mysqli_stmt_affected_rows($stmt) > 0) {
                            $message = "<p style='color:green;'>Department ID {$department_id} deleted successfully!</p>";
                        } else {
                            $message = "<p style='color:blue;'>No department found with ID {$department_id} to delete.</p>";
                        }
                    } else {
                        $message = "<p style='color:red;'>Error deleting department: " . mysqli_error($conn) . "</p>";
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $message = "<p style='color:red;'>Error preparing statement: " . mysqli_error($conn) . "</p>";
                }
                $action = 'view_all_departments';
                break;


            case 'create_professor':
                $name = sanitize_input($_POST['name']);
                $surname = sanitize_input($_POST['surname']);
                $email = sanitize_input($_POST['email']);
                $dept_id = sanitize_input($_POST['dept_id']);

                if (!is_numeric($dept_id)) {
                    $message = "<p style='color:red;'>Invalid Department ID.</p>";
                    break;
                }

                $sql = "INSERT INTO Professor (name, surname, email, dept_id) VALUES (?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "sssi", $name, $surname, $email, $dept_id);
                    if (mysqli_stmt_execute($stmt)) {
                        $message = "<p style='color:green;'>Professor '{$name} {$surname}' added successfully!</p>";
                    } else {
                        $message = "<p style='color:red;'>Error adding professor: " . mysqli_error($conn) . "</p>";
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $message = "<p style='color:red;'>Error preparing statement: " . mysqli_error($conn) . "</p>";
                }
                $action = 'view_all_professors';
                break;

            case 'update_professor':
                $professor_id = sanitize_input($_POST['professor_id']);
                $name = sanitize_input($_POST['name']);
                $surname = sanitize_input($_POST['surname']);
                $email = sanitize_input($_POST['email']);
                $dept_id = sanitize_input($_POST['dept_id']);

                if (!is_numeric($professor_id) || !is_numeric($dept_id)) {
                    $message = "<p style='color:red;'>Invalid Professor ID or Department ID for update.</p>";
                    break;
                }

                $sql = "UPDATE Professor SET name = ?, surname = ?, email = ?, dept_id = ? WHERE professor_id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "sssii", $name, $surname, $email, $dept_id, $professor_id);
                    if (mysqli_stmt_execute($stmt)) {
                        $message = "<p style='color:green;'>Professor ID {$professor_id} updated successfully!</p>";
                    } else {
                        $message = "<p style='color:red;'>Error updating professor: " . mysqli_error($conn) . "</p>";
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $message = "<p style='color:red;'>Error preparing statement: " . mysqli_error($conn) . "</p>";
                }
                $action = 'view_all_professors';
                break;

            case 'delete_professor':
                $professor_id = sanitize_input($_POST['professor_id']);
                if (!is_numeric($professor_id)) {
                    $message = "<p style='color:red;'>Invalid Professor ID for deletion.</p>";
                    break;
                }
                $sql = "DELETE FROM Professor WHERE professor_id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "i", $professor_id);
                    if (mysqli_stmt_execute($stmt)) {
                        if (mysqli_stmt_affected_rows($stmt) > 0) {
                            $message = "<p style='color:green;'>Professor ID {$professor_id} deleted successfully!</p>";
                        } else {
                            $message = "<p style='color:blue;'>No professor found with ID {$professor_id} to delete.</p>";
                        }
                    } else {
                        $message = "<p style='color:red;'>Error deleting professor: " . mysqli_error($conn) . "</p>";
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $message = "<p style='color:red;'>Error preparing statement: " . mysqli_error($conn) . "</p>";
                }
                $action = 'view_all_professors';
                break;


            case 'create_course':
                $course_code = sanitize_input($_POST['course_code']);
                $course_name = sanitize_input($_POST['course_name']);
                $credits = sanitize_input($_POST['credits']);
                $prof_id = sanitize_input($_POST['prof_id']);

                if (!is_numeric($credits) || (!empty($prof_id) && !is_numeric($prof_id))) {
                    $message = "<p style='color:red;'>Invalid Credits or Professor ID.</p>";
                    break;
                }
                $prof_id_val = empty($prof_id) ? NULL : (int)$prof_id;

                $sql = "INSERT INTO Course (course_code, course_name, credits, prof_id) VALUES (?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt) {
                    if ($prof_id_val === NULL) {
                        mysqli_stmt_bind_param($stmt, "ssds", $course_code, $course_name, $credits, $prof_id_val);
                    } else {
                        mysqli_stmt_bind_param($stmt, "ssdi", $course_code, $course_name, $credits, $prof_id_val);
                    }

                    if (mysqli_stmt_execute($stmt)) {
                        $message = "<p style='color:green;'>Course '{$course_code} - {$course_name}' added successfully!</p>";
                    } else {
                        $message = "<p style='color:red;'>Error adding course: " . mysqli_error($conn) . "</p>";
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $message = "<p style='color:red;'>Error preparing statement: " . mysqli_error($conn) . "</p>";
                }
                $action = 'view_all_courses';
                break;

            case 'update_course':
                $course_id = sanitize_input($_POST['course_id']);
                $course_code = sanitize_input($_POST['course_code']);
                $course_name = sanitize_input($_POST['course_name']);
                $credits = sanitize_input($_POST['credits']);
                $prof_id = sanitize_input($_POST['prof_id']);

                if (!is_numeric($course_id) || !is_numeric($credits) || (!empty($prof_id) && !is_numeric($prof_id))) {
                    $message = "<p style='color:red;'>Invalid Course ID, Credits, or Professor ID for update.</p>";
                    break;
                }
                $prof_id_val = empty($prof_id) ? NULL : (int)$prof_id;

                $sql = "UPDATE Course SET course_code = ?, course_name = ?, credits = ?, prof_id = ? WHERE course_id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt) {
                    if ($prof_id_val === NULL) {
                        mysqli_stmt_bind_param($stmt, "ssdss", $course_code, $course_name, $credits, $prof_id_val, $course_id);
                    } else {
                        mysqli_stmt_bind_param($stmt, "ssdis", $course_code, $course_name, $credits, $prof_id_val, $course_id);
                    }

                    if (mysqli_stmt_execute($stmt)) {
                        $message = "<p style='color:green;'>Course ID {$course_id} updated successfully!</p>";
                    } else {
                        $message = "<p style='color:red;'>Error updating course: " . mysqli_error($conn) . "</p>";
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $message = "<p style='color:red;'>Error preparing statement: " . mysqli_error($conn) . "</p>";
                }
                $action = 'view_all_courses';
                break;

            case 'delete_course':
                $course_id = sanitize_input($_POST['course_id']);
                if (!is_numeric($course_id)) {
                    $message = "<p style='color:red;'>Invalid Course ID for deletion.</p>";
                    break;
                }
                $sql = "DELETE FROM Course WHERE course_id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "i", $course_id);
                    if (mysqli_stmt_execute($stmt)) {
                        if (mysqli_stmt_affected_rows($stmt) > 0) {
                            $message = "<p style='color:green;'>Course ID {$course_id} deleted successfully!</p>";
                        } else {
                            $message = "<p style='color:blue;'>No course found with ID {$course_id} to delete.</p>";
                        }
                    } else {
                        $message = "<p style='color:red;'>Error deleting course: " . mysqli_error($conn) . "</p>";
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $message = "<p style='color:red;'>Error preparing statement: " . mysqli_error($conn) . "</p>";
                }
                $action = 'view_all_courses';
                break;

            case 'create_subject':
                $subject_name = sanitize_input($_POST['subject_name']);
                $course_id = sanitize_input($_POST['course_id']);

                if (!is_numeric($course_id)) {
                    $message = "<p style='color:red;'>Invalid Course ID.</p>";
                    break;
                }

                $sql = "INSERT INTO Subject (subject_name, course_id) VALUES (?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "si", $subject_name, $course_id);
                    if (mysqli_stmt_execute($stmt)) {
                        $message = "<p style='color:green;'>Subject '{$subject_name}' added successfully!</p>";
                    } else {
                        $message = "<p style='color:red;'>Error adding subject: " . mysqli_error($conn) . "</p>";
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $message = "<p style='color:red;'>Error preparing statement: " . mysqli_error($conn) . "</p>";
                }
                $action = 'view_all_subjects';
                break;

            case 'update_subject':
                $subject_id = sanitize_input($_POST['subject_id']);
                $subject_name = sanitize_input($_POST['subject_name']);
                $course_id = sanitize_input($_POST['course_id']);

                if (!is_numeric($subject_id) || !is_numeric($course_id)) {
                    $message = "<p style='color:red;'>Invalid Subject ID or Course ID for update.</p>";
                    break;
                }

                $sql = "UPDATE Subject SET subject_name = ?, course_id = ? WHERE subject_id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "sii", $subject_name, $course_id, $subject_id);
                    if (mysqli_stmt_execute($stmt)) {
                        $message = "<p style='color:green;'>Subject ID {$subject_id} updated successfully!</p>";
                    } else {
                        $message = "<p style='color:red;'>Error updating subject: " . mysqli_error($conn) . "</p>";
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $message = "<p style='color:red;'>Error preparing statement: " . mysqli_error($conn) . "</p>";
                }
                $action = 'view_all_subjects';
                break;

            case 'delete_subject':
                $subject_id = sanitize_input($_POST['subject_id']);
                if (!is_numeric($subject_id)) {
                    $message = "<p style='color:red;'>Invalid Subject ID for deletion.</p>";
                    break;
                }
                $sql = "DELETE FROM Subject WHERE subject_id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "i", $subject_id);
                    if (mysqli_stmt_execute($stmt)) {
                        if (mysqli_stmt_affected_rows($stmt) > 0) {
                            $message = "<p style='color:green;'>Subject ID {$subject_id} deleted successfully!</p>";
                        } else {
                            $message = "<p style='color:blue;'>No subject found with ID {$subject_id} to delete.</p>";
                        }
                    } else {
                        $message = "<p style='color:red;'>Error deleting subject: " . mysqli_error($conn) . "</p>";
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $message = "<p style='color:red;'>Error preparing statement: " . mysqli_error($conn) . "</p>";
                }
                $action = 'view_all_subjects';
                break;

            case 'create_exam':
                $exam_name = sanitize_input($_POST['exam_name']);
                $subject_id = sanitize_input($_POST['subject_id']);

                if (!is_numeric($subject_id)) {
                    $message = "<p style='color:red;'>Invalid Subject ID.</p>";
                    break;
                }

                $sql = "INSERT INTO Exam (exam_name, subject_id) VALUES (?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "si", $exam_name, $subject_id);
                    if (mysqli_stmt_execute($stmt)) {
                        $message = "<p style='color:green;'>Exam '{$exam_name}' added successfully!</p>";
                    } else {
                        $message = "<p style='color:red;'>Error adding exam: " . mysqli_error($conn) . "</p>";
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $message = "<p style='color:red;'>Error preparing statement: " . mysqli_error($conn) . "</p>";
                }
                $action = 'view_all_exams';
                break;

            case 'update_exam':
                $exam_id = sanitize_input($_POST['exam_id']);
                $exam_name = sanitize_input($_POST['exam_name']);
                $subject_id = sanitize_input($_POST['subject_id']);

                if (!is_numeric($exam_id) || !is_numeric($subject_id)) {
                    $message = "<p style='color:red;'>Invalid Exam ID or Subject ID for update.</p>";
                    break;
                }

                $sql = "UPDATE Exam SET exam_name = ?, subject_id = ? WHERE exam_id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "sii", $exam_name, $subject_id, $exam_id);
                    if (mysqli_stmt_execute($stmt)) {
                        $message = "<p style='color:green;'>Exam ID {$exam_id} updated successfully!</p>";
                    } else {
                        $message = "<p style='color:red;'>Error updating exam: " . mysqli_error($conn) . "</p>";
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $message = "<p style='color:red;'>Error preparing statement: " . mysqli_error($conn) . "</p>";
                }
                $action = 'view_all_exams';
                break;

            case 'delete_exam':
                $exam_id = sanitize_input($_POST['exam_id']);
                if (!is_numeric($exam_id)) {
                    $message = "<p style='color:red;'>Invalid Exam ID for deletion.</p>";
                    break;
                }
                $sql = "DELETE FROM Exam WHERE exam_id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "i", $exam_id);
                    if (mysqli_stmt_execute($stmt)) {
                        if (mysqli_stmt_affected_rows($stmt) > 0) {
                            $message = "<p style='color:green;'>Exam ID {$exam_id} deleted successfully!</p>";
                        } else {
                            $message = "<p style='color:blue;'>No exam found with ID {$exam_id} to delete.</p>";
                        }
                    } else {
                        $message = "<p style='color:red;'>Error deleting exam: " . mysqli_error($conn) . "</p>";
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $message = "<p style='color:red;'>Error preparing statement: " . mysqli_error($conn) . "</p>";
                }
                $action = 'view_all_exams';
                break;

            case 'enroll_student':
                $student_id = sanitize_input($_POST['student_id']);
                $course_id = sanitize_input($_POST['course_id']);
                $enrollment_date = date('Y-m-d');

                if (!is_numeric($student_id) || !is_numeric($course_id)) {
                    $message = "<p style='color:red;'>Invalid Student ID or Course ID.</p>";
                    break;
                }

                $sql = "INSERT INTO Enrollment (student_id, course_id, enrollment_date) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "iis", $student_id, $course_id, $enrollment_date);
                    if (mysqli_stmt_execute($stmt)) {
                        $message = "<p style='color:green;'>Student ID {$student_id} enrolled in Course ID {$course_id} successfully!</p>";
                    } else {
                        $message = "<p style='color:red;'>Error enrolling student: " . mysqli_error($conn) . "</p>";
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $message = "<p style='color:red;'>Error preparing statement: " . mysqli_error($conn) . "</p>";
                }
                $action = 'enroll_student_form';
                break;

            case 'update_enrollment':
                $enrollment_id = sanitize_input($_POST['enrollment_id']);
                $final_grade_for_course = sanitize_input($_POST['final_grade_for_course']);
                $status = sanitize_input($_POST['status']);

                if (!is_numeric($enrollment_id)) {
                    $message = "<p style='color:red;'>Invalid Enrollment ID for update.</p>";
                    break;
                }

                $sql = "UPDATE Enrollment SET final_grade_for_course = ?, status = ? WHERE enrollment_id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "ssi", $final_grade_for_course, $status, $enrollment_id);
                    if (mysqli_stmt_execute($stmt)) {
                        $message = "<p style='color:green;'>Enrollment ID {$enrollment_id} updated successfully!</p>";
                    } else {
                        $message = "<p style='color:red;'>Error updating enrollment: " . mysqli_error($conn) . "</p>";
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $message = "<p style='color:red;'>Error preparing statement: " . mysqli_error($conn) . "</p>";
                }
                $action = 'view_all_enrollments';
                break;

            case 'delete_enrollment':
                $enrollment_id = sanitize_input($_POST['enrollment_id']);
                if (!is_numeric($enrollment_id)) {
                    $message = "<p style='color:red;'>Invalid Enrollment ID for deletion.</p>";
                    break;
                }
                $sql = "DELETE FROM Enrollment WHERE enrollment_id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "i", $enrollment_id);
                    if (mysqli_stmt_execute($stmt)) {
                        if (mysqli_stmt_affected_rows($stmt) > 0) {
                            $message = "<p style='color:green;'>Enrollment ID {$enrollment_id} deleted successfully!</p>";
                        } else {
                            $message = "<p style='color:blue;'>No enrollment found with ID {$enrollment_id} to delete.</p>";
                        }
                    } else {
                        $message = "<p style='color:red;'>Error deleting enrollment: " . mysqli_error($conn) . "</p>";
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $message = "<p style='color:red;'>Error preparing statement: " . mysqli_error($conn) . "</p>";
                }
                $action = 'view_all_enrollments';
                break;

            case 'record_exam_score':
                $student_id = sanitize_input($_POST['student_id']);
                $exam_id = sanitize_input($_POST['exam_id']);
                $score = sanitize_input($_POST['score']);
                $letter_grade = sanitize_input($_POST['letter_grade']);
                $exam_date_taken = date('Y-m-d H:i:s');

                if (!is_numeric($student_id) || !is_numeric($exam_id) || !is_numeric($score)) {
                    $message = "<p style='color:red;'>Invalid Student ID, Exam ID, or Score.</p>";
                    break;
                }

                $sql = "INSERT INTO StudentExam (student_id, exam_id, score, letter_grade, exam_date_taken) VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "iidis", $student_id, $exam_id, $score, $letter_grade, $exam_date_taken);
                    if (mysqli_stmt_execute($stmt)) {
                        $message = "<p style='color:green;'>Score recorded for Student ID {$student_id} on Exam ID {$exam_id} successfully!</p>";
                    } else {
                        $message = "<p style='color:red;'>Error recording exam score: " . mysqli_error($conn) . "</p>";
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $message = "<p style='color:red;'>Error preparing statement: " . mysqli_error($conn) . "</p>";
                }
                $action = 'record_exam_score_form';
                break;

            case 'update_student_exam':
                $student_exam_id = sanitize_input($_POST['student_exam_id']);
                $score = sanitize_input($_POST['score']);
                $letter_grade = sanitize_input($_POST['letter_grade']);
                $exam_date_taken = sanitize_input($_POST['exam_date_taken']);

                if (!is_numeric($student_exam_id) || !is_numeric($score)) {
                    $message = "<p style='color:red;'>Invalid Student Exam ID or Score for update.</p>";
                    break;
                }

                $sql = "UPDATE StudentExam SET score = ?, letter_grade = ?, exam_date_taken = ? WHERE student_exam_id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "dsii", $score, $letter_grade, $exam_date_taken, $student_exam_id);
                    if (mysqli_stmt_execute($stmt)) {
                        $message = "<p style='color:green;'>Student Exam ID {$student_exam_id} updated successfully!</p>";
                    } else {
                        $message = "<p style='color:red;'>Error updating exam score: " . mysqli_error($conn) . "</p>";
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $message = "<p style='color:red;'>Error preparing statement: " . mysqli_error($conn) . "</p>";
                }
                $action = 'view_all_student_exams';
                break;

            case 'delete_student_exam':
                $student_exam_id = sanitize_input($_POST['student_exam_id']);
                if (!is_numeric($student_exam_id)) {
                    $message = "<p style='color:red;'>Invalid Student Exam ID for deletion.</p>";
                    break;
                }
                $sql = "DELETE FROM StudentExam WHERE student_exam_id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "i", $student_exam_id);
                    if (mysqli_stmt_execute($stmt)) {
                        if (mysqli_stmt_affected_rows($stmt) > 0) {
                            $message = "<p style='color:green;'>Exam Score ID {$student_exam_id} deleted successfully!</p>";
                        } else {
                            $message = "<p style='color:blue;'>No exam score found with ID {$student_exam_id} to delete.</p>";
                        }
                    } else {
                        $message = "<p style='color:red;'>Error deleting exam score: " . mysqli_error($conn) . "</p>";
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $message = "<p style='color:red;'>Error preparing statement: " . mysqli_error($conn) . "</p>";
                }
                $action = 'view_all_student_exams';
                break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Management App</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        .container { max-width: 960px; margin: auto; padding: 20px; border: 1px solid #ccc; }
        h1, h2 { color: #333; }
        nav { margin-bottom: 20px; }
        nav a { margin-right: 15px; text-decoration: none; color: blue; }
        nav a:hover { text-decoration: underline; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        form { margin-top: 20px; padding: 10px; border: 1px solid #eee; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], input[type="email"], input[type="number"], select { padding: 5px; margin-bottom: 10px; width: 250px; }
        input[type="submit"], button { padding: 8px 12px; background-color: #4CAF50; color: white; border: none; cursor: pointer; margin-right: 5px; }
        input[type="submit"]:hover, button:hover { background-color: #45a049; }
    </style>
</head>
<body>

<div class="container">
    <h1>University Management System</h1>

    <nav>
        <a href="?action=view_all_students">Students</a> |
        <a href="?action=view_all_departments">Departments</a> |
        <a href="?action=view_all_professors">Professors</a> |
        <a href="?action=view_all_courses">Courses</a> |
        <a href="?action=view_all_subjects">Subjects</a> |
        <a href="?action=view_all_exams">Exams</a> |
        <a href="?action=view_all_enrollments">Enrollments</a> |
        <a href="?action=view_all_student_exams">Exam Scores</a>
    </nav>

    <?php
    if (!empty($message)) {
        echo $message;
    }
    ?>

    <?php
    switch ($action) {
        // --- Student Views ---
        case 'view_all_students':
            echo "<h2>All Students</h2>";
            echo "<p><a href='?action=create_student_form'>Add New Student</a></p>";
            $sql = "SELECT student_id, name, surname, email FROM Student";
            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                echo "<table>";
                echo "<thead><tr><th>ID</th><th>Name</th><th>Surname</th><th>Email</th><th>Actions</th></tr></thead>";
                echo "<tbody>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['student_id'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['surname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>";
                    echo "<a href='?action=update_student_form&id=" . $row['student_id'] . "'>Edit</a> ";
                    echo "<form method='post' action='?action=delete_student' style='display:inline-block;'>";
                    echo "<input type='hidden' name='form_type' value='delete_student'>";
                    echo "<input type='hidden' name='student_id' value='" . $row['student_id'] . "'>";
                    echo "<button type='submit'>Delete</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>No students found.</p>";
            }
            break;

        case 'create_student_form':
            ?>
            <h2>Add New Student</h2>
            <form method="post" action="">
                <input type="hidden" name="form_type" value="create_student">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required><br>
                <label for="surname">Surname:</label>
                <input type="text" name="surname" id="surname" required><br>
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required><br><br>
                <input type="submit" value="Add Student">
            </form>
            <?php
            break;

        case 'update_student_form':
            $student_id_to_edit = isset($_GET['id']) ? sanitize_input($_GET['id']) : '';
            $student_data = [];

            if (!empty($student_id_to_edit) && is_numeric($student_id_to_edit)) {
                $sql = "SELECT student_id, name, surname, email FROM Student WHERE student_id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "i", $student_id_to_edit);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    if (mysqli_num_rows($result) > 0) {
                        $student_data = mysqli_fetch_assoc($result);
                    } else {
                        $message = "<p style='color:red;'>Student not found for editing.</p>";
                    }
                    mysqli_stmt_close($stmt);
                }
            }
            ?>
            <h2>Update Student Details</h2>
            <form method="post" action="">
                <input type="hidden" name="form_type" value="update_student">
                <label for="update_student_id">Student ID:</label>
                <input type="text" name="student_id" id="update_student_id" value="<?php echo ($student_data['student_id'] ?? ''); ?>" readonly required><br>
                <label for="update_name">Name:</label>
                <input type="text" name="name" id="update_name" value="<?php echo htmlspecialchars($student_data['name'] ?? ''); ?>" required><br>
                <label for="update_surname">Surname:</label>
                <input type="text" name="surname" id="update_surname" value="<?php echo htmlspecialchars($student_data['surname'] ?? ''); ?>" required><br>
                <label for="update_email">Email:</label>
                <input type="email" name="email" id="update_email" value="<?php echo htmlspecialchars($student_data['email'] ?? ''); ?>" required><br><br>
                <input type="submit" value="Update Student">
            </form>
            <?php
            break;

        case 'delete_student_form':
            ?>
            <h2>Delete Student</h2>
            <form method="post" action="">
                <input type="hidden" name="form_type" value="delete_student">
                <label for="delete_student_id">Student ID to Delete:</label>
                <input type="text" name="student_id" id="delete_student_id" required><br><br>
                <input type="submit" value="Delete Student">
            </form>
            <?php
            break;

        // --- Department Views ---
        case 'view_all_departments':
            echo "<h2>All Departments</h2>";
            echo "<p><a href='?action=create_department_form'>Add New Department</a></p>";
            $sql = "SELECT department_id, department_name, department_head, location FROM Department";
            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                echo "<table>";
                echo "<thead><tr><th>ID</th><th>Name</th><th>Head</th><th>Location</th><th>Actions</th></tr></thead>";
                echo "<tbody>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['department_id'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['department_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['department_head']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                    echo "<td>";
                    echo "<a href='?action=update_department_form&id=" . $row['department_id'] . "'>Edit</a> ";
                    echo "<form method='post' action='?action=delete_department' style='display:inline-block;'>";
                    echo "<input type='hidden' name='form_type' value='delete_department'>";
                    echo "<input type='hidden' name='department_id' value='" . $row['department_id'] . "'>";
                    echo "<button type='submit'>Delete</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>No departments found.</p>";
            }
            break;

        case 'create_department_form':
            ?>
            <h2>Add New Department</h2>
            <form method="post" action="">
                <input type="hidden" name="form_type" value="create_department">
                <label for="dept_name">Department Name:</label>
                <input type="text" name="department_name" id="dept_name" required><br>
                <label for="dept_head">Department Head (Name):</label>
                <input type="text" name="department_head" id="dept_head"><br>
                <label for="dept_location">Location:</label>
                <input type="text" name="location" id="dept_location"><br><br>
                <input type="submit" value="Add Department">
            </form>
            <?php
            break;

        case 'update_department_form':
            $department_id_to_edit = isset($_GET['id']) ? sanitize_input($_GET['id']) : '';
            $department_data = [];

            if (!empty($department_id_to_edit) && is_numeric($department_id_to_edit)) {
                $sql = "SELECT department_id, department_name, department_head, location FROM Department WHERE department_id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "i", $department_id_to_edit);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    if (mysqli_num_rows($result) > 0) {
                        $department_data = mysqli_fetch_assoc($result);
                    } else {
                        $message = "<p style='color:red;'>Department not found for editing.</p>";
                    }
                    mysqli_stmt_close($stmt);
                }
            }
            ?>
            <h2>Update Department Details</h2>
            <form method="post" action="">
                <input type="hidden" name="form_type" value="update_department">
                <label for="update_dept_id">Department ID:</label>
                <input type="text" name="department_id" id="update_dept_id" value="<?php echo ($department_data['department_id'] ?? ''); ?>" readonly required><br>
                <label for="update_dept_name">Department Name:</label>
                <input type="text" name="department_name" id="update_dept_name" value="<?php echo htmlspecialchars($department_data['department_name'] ?? ''); ?>" required><br>
                <label for="update_dept_head">Department Head (Name):</label>
                <input type="text" name="department_head" id="update_dept_head" value="<?php echo htmlspecialchars($department_data['department_head'] ?? ''); ?>"><br>
                <label for="update_dept_location">Location:</label>
                <input type="text" name="location" id="update_dept_location" value="<?php echo htmlspecialchars($department_data['location'] ?? ''); ?>"><br><br>
                <input type="submit" value="Update Department">
            </form>
            <?php
            break;

        case 'delete_department_form':
            ?>
            <h2>Delete Department</h2>
            <form method="post" action="">
                <input type="hidden" name="form_type" value="delete_department">
                <label for="delete_dept_id">Department ID to Delete:</label>
                <input type="text" name="department_id" id="delete_dept_id" required><br><br>
                <input type="submit" value="Delete Department">
            </form>
            <?php
            break;

        // --- Professor Views ---
        case 'view_all_professors':
            echo "<h2>All Professors</h2>";
            echo "<p><a href='?action=create_professor_form'>Add New Professor</a></p>";
            $sql = "SELECT P.professor_id, P.name, P.surname, P.email, D.department_name FROM Professor P JOIN Department D ON P.dept_id = D.department_id";
            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                echo "<table>";
                echo "<thead><tr><th>ID</th><th>Name</th><th>Surname</th><th>Email</th><th>Department</th><th>Actions</th></tr></thead>";
                echo "<tbody>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['professor_id'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['surname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['department_name']) . "</td>";
                    echo "<td>";
                    echo "<a href='?action=update_professor_form&id=" . $row['professor_id'] . "'>Edit</a> ";
                    echo "<form method='post' action='?action=delete_professor' style='display:inline-block;'>";
                    echo "<input type='hidden' name='form_type' value='delete_professor'>";
                    echo "<input type='hidden' name='professor_id' value='" . $row['professor_id'] . "'>";
                    echo "<button type='submit'>Delete</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>No professors found.</p>";
            }
            break;

        case 'create_professor_form':
            $departments = [];
            $sql_depts = "SELECT department_id, department_name FROM Department ORDER BY department_name";
            $result_depts = mysqli_query($conn, $sql_depts);
            if ($result_depts) {
                while ($row = mysqli_fetch_assoc($result_depts)) {
                    $departments[] = $row;
                }
            }
            ?>
            <h2>Add New Professor</h2>
            <form method="post" action="">
                <input type="hidden" name="form_type" value="create_professor">
                <label for="prof_name">Name:</label>
                <input type="text" name="name" id="prof_name" required><br>
                <label for="prof_surname">Surname:</label>
                <input type="text" name="surname" id="prof_surname" required><br>
                <label for="prof_email">Email:</label>
                <input type="email" name="email" id="prof_email"><br>
                <label for="prof_dept">Department:</label>
                <select name="dept_id" id="prof_dept" required>
                    <option value="">-- Select Department --</option>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?php echo $dept['department_id']; ?>">
                            <?php echo htmlspecialchars($dept['department_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select><br><br>
                <input type="submit" value="Add Professor">
            </form>
            <?php
            break;

        case 'update_professor_form':
            $professor_id_to_edit = isset($_GET['id']) ? sanitize_input($_GET['id']) : '';
            $professor_data = [];
            $departments = []; // For dropdown

            $sql_depts = "SELECT department_id, department_name FROM Department ORDER BY department_name";
            $result_depts = mysqli_query($conn, $sql_depts);
            if ($result_depts) {
                while ($row = mysqli_fetch_assoc($result_depts)) {
                    $departments[] = $row;
                }
            }

            if (!empty($professor_id_to_edit) && is_numeric($professor_id_to_edit)) {
                $sql = "SELECT professor_id, name, surname, email, dept_id FROM Professor WHERE professor_id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "i", $professor_id_to_edit);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    if (mysqli_num_rows($result) > 0) {
                        $professor_data = mysqli_fetch_assoc($result);
                    } else {
                        $message = "<p style='color:red;'>Professor not found for editing.</p>";
                    }
                    mysqli_stmt_close($stmt);
                }
            }
            ?>
            <h2>Update Professor Details</h2>
            <form method="post" action="">
                <input type="hidden" name="form_type" value="update_professor">
                <label for="update_prof_id">Professor ID:</label>
                <input type="text" name="professor_id" id="update_prof_id" value="<?php echo ($professor_data['professor_id'] ?? ''); ?>" readonly required><br>
                <label for="update_prof_name">Name:</label>
                <input type="text" name="name" id="update_prof_name" value="<?php echo htmlspecialchars($professor_data['name'] ?? ''); ?>" required><br>
                <label for="update_prof_surname">Surname:</label>
                <input type="text" name="surname" id="update_prof_surname" value="<?php echo htmlspecialchars($professor_data['surname'] ?? ''); ?>" required><br>
                <label for="update_prof_email">Email:</label>
                <input type="email" name="email" id="update_prof_email" value="<?php echo htmlspecialchars($professor_data['email'] ?? ''); ?>"><br>
                <label for="update_prof_dept">Department:</label>
                <select name="dept_id" id="update_prof_dept" required>
                    <option value="">-- Select Department --</option>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?php echo $dept['department_id']; ?>" <?php echo (isset($professor_data['dept_id']) && $professor_data['dept_id'] == $dept['department_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($dept['department_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select><br><br>
                <input type="submit" value="Update Professor">
            </form>
            <?php
            break;

        case 'delete_professor_form':
            ?>
            <h2>Delete Professor</h2>
            <form method="post" action="">
                <input type="hidden" name="form_type" value="delete_professor">
                <label for="delete_prof_id">Professor ID to Delete:</label>
                <input type="text" name="professor_id" id="delete_prof_id" required><br><br>
                <input type="submit" value="Delete Professor">
            </form>
            <?php
            break;

        // --- Course Views ---
        case 'view_all_courses':
            echo "<h2>All Courses</h2>";
            echo "<p><a href='?action=create_course_form'>Add New Course</a></p>";
            $sql = "SELECT C.course_id, C.course_code, C.course_name, C.credits, P.name AS prof_name, P.surname AS prof_surname FROM Course C LEFT JOIN Professor P ON C.prof_id = P.professor_id";
            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                echo "<table>";
                echo "<thead><tr><th>ID</th><th>Code</th><th>Name</th><th>Credits</th><th>Professor</th><th>Actions</th></tr></thead>";
                echo "<tbody>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['course_id'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['course_code']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['course_name']) . "</td>";
                    echo "<td>" . $row['credits'] . "</td>";
                    echo "<td>";
                    if (!empty($row['prof_name'])) {
                        echo htmlspecialchars($row['prof_name'] . ' ' . $row['prof_surname']);
                    } else {
                        echo "N/A";
                    }
                    echo "</td>";
                    echo "<td>";
                    echo "<a href='?action=update_course_form&id=" . $row['course_id'] . "'>Edit</a> ";
                    echo "<form method='post' action='?action=delete_course' style='display:inline-block;'>";
                    echo "<input type='hidden' name='form_type' value='delete_course'>";
                    echo "<input type='hidden' name='course_id' value='" . $row['course_id'] . "'>";
                    echo "<button type='submit'>Delete</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>No courses found.</p>";
            }
            break;

        case 'create_course_form':
            $professors = [];
            $sql_profs = "SELECT professor_id, name, surname FROM Professor ORDER BY name";
            $result_profs = mysqli_query($conn, $sql_profs);
            if ($result_profs) {
                while ($row = mysqli_fetch_assoc($result_profs)) {
                    $professors[] = $row;
                }
            }
            ?>
            <h2>Add New Course</h2>
            <form method="post" action="">
                <input type="hidden" name="form_type" value="create_course">
                <label for="course_code">Course Code:</label>
                <input type="text" name="course_code" id="course_code" required><br>
                <label for="course_name">Course Name:</label>
                <input type="text" name="course_name" id="course_name" required><br>
                <label for="credits">Credits:</label>
                <input type="number" step="0.1" name="credits" id="credits" required><br>
                <label for="course_prof">Professor:</label>
                <select name="prof_id" id="course_prof">
                    <option value="">-- Select Professor (Optional) --</option>
                    <?php foreach ($professors as $prof): ?>
                        <option value="<?php echo $prof['professor_id']; ?>">
                            <?php echo htmlspecialchars($prof['name'] . ' ' . $prof['surname']); ?>
                        </option>
                    <?php endforeach; ?>
                </select><br><br>
                <input type="submit" value="Add Course">
            </form>
            <?php
            break;

        case 'update_course_form':
            $course_id_to_edit = isset($_GET['id']) ? sanitize_input($_GET['id']) : '';
            $course_data = [];
            $professors = []; // For dropdown

            $sql_profs = "SELECT professor_id, name, surname FROM Professor ORDER BY name";
            $result_profs = mysqli_query($conn, $sql_profs);
            if ($result_profs) {
                while ($row = mysqli_fetch_assoc($result_profs)) {
                    $professors[] = $row;
                }
            }

            if (!empty($course_id_to_edit) && is_numeric($course_id_to_edit)) {
                $sql = "SELECT course_id, course_code, course_name, credits, prof_id FROM Course WHERE course_id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "i", $course_id_to_edit);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    if (mysqli_num_rows($result) > 0) {
                        $course_data = mysqli_fetch_assoc($result);
                    } else {
                        $message = "<p style='color:red;'>Course not found for editing.</p>";
                    }
                    mysqli_stmt_close($stmt);
                }
            }
            ?>
            <h2>Update Course Details</h2>
            <form method="post" action="">
                <input type="hidden" name="form_type" value="update_course">
                <label for="update_course_id">Course ID:</label>
                <input type="text" name="course_id" id="update_course_id" value="<?php echo ($course_data['course_id'] ?? ''); ?>" readonly required><br>
                <label for="update_course_code">Course Code:</label>
                <input type="text" name="course_code" id="update_course_code" value="<?php echo htmlspecialchars($course_data['course_code'] ?? ''); ?>" required><br>
                <label for="update_course_name">Course Name:</label>
                <input type="text" name="course_name" id="update_course_name" value="<?php echo htmlspecialchars($course_data['course_name'] ?? ''); ?>" required><br>
                <label for="update_credits">Credits:</label>
                <input type="number" step="0.1" name="credits" id="update_credits" value="<?php echo htmlspecialchars($course_data['credits'] ?? ''); ?>" required><br>
                <label for="update_course_prof">Professor:</label>
                <select name="prof_id" id="update_course_prof">
                    <option value="">-- Select Professor (Optional) --</option>
                    <?php foreach ($professors as $prof): ?>
                        <option value="<?php echo $prof['professor_id']; ?>" <?php echo (isset($course_data['prof_id']) && $course_data['prof_id'] == $prof['professor_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($prof['name'] . ' ' . $prof['surname']); ?>
                        </option>
                    <?php endforeach; ?>
                </select><br><br>
                <input type="submit" value="Update Course">
            </form>
            <?php
            break;

        case 'delete_course_form':
            ?>
            <h2>Delete Course</h2>
            <form method="post" action="">
                <input type="hidden" name="form_type" value="delete_course">
                <label for="delete_course_id">Course ID to Delete:</label>
                <input type="text" name="course_id" id="delete_course_id" required><br><br>
                <input type="submit" value="Delete Course">
            </form>
            <?php
            break;

        // --- Subject Views ---
        case 'view_all_subjects':
            echo "<h2>All Subjects</h2>";
            echo "<p><a href='?action=create_subject_form'>Add New Subject</a></p>";
            $sql = "SELECT S.subject_id, S.subject_name, C.course_code, C.course_name FROM Subject S JOIN Course C ON S.course_id = C.course_id ORDER BY C.course_code, S.subject_name";
            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                echo "<table>";
                echo "<thead><tr><th>ID</th><th>Subject Name</th><th>Course Code</th><th>Course Name</th><th>Actions</th></tr></thead>";
                echo "<tbody>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['subject_id'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['subject_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['course_code']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['course_name']) . "</td>";
                    echo "<td>";
                    echo "<a href='?action=update_subject_form&id=" . $row['subject_id'] . "'>Edit</a> ";
                    echo "<form method='post' action='?action=delete_subject' style='display:inline-block;'>";
                    echo "<input type='hidden' name='form_type' value='delete_subject'>";
                    echo "<input type='hidden' name='subject_id' value='" . $row['subject_id'] . "'>";
                    echo "<button type='submit'>Delete</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>No subjects found.</p>";
            }
            break;

        case 'create_subject_form':
            $courses = [];
            $sql_courses = "SELECT course_id, course_code, course_name FROM Course ORDER BY course_code";
            $result_courses = mysqli_query($conn, $sql_courses);
            if ($result_courses) {
                while ($row = mysqli_fetch_assoc($result_courses)) {
                    $courses[] = $row;
                }
            }
            ?>
            <h2>Add New Subject</h2>
            <form method="post" action="">
                <input type="hidden" name="form_type" value="create_subject">
                <label for="subject_name">Subject Name:</label>
                <input type="text" name="subject_name" id="subject_name" required><br>
                <label for="subject_course">Course:</label>
                <select name="course_id" id="subject_course" required>
                    <option value="">-- Select Course --</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?php echo $course['course_id']; ?>">
                            <?php echo htmlspecialchars($course['course_code'] . ' - ' . $course['course_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select><br><br>
                <input type="submit" value="Add Subject">
            </form>
            <?php
            break;

        case 'update_subject_form':
            $subject_id_to_edit = isset($_GET['id']) ? sanitize_input($_GET['id']) : '';
            $subject_data = [];
            $courses = []; // For dropdown

            $sql_courses = "SELECT course_id, course_code, course_name FROM Course ORDER BY course_code";
            $result_courses = mysqli_query($conn, $sql_courses);
            if ($result_courses) {
                while ($row = mysqli_fetch_assoc($result_courses)) {
                    $courses[] = $row;
                }
            }

            if (!empty($subject_id_to_edit) && is_numeric($subject_id_to_edit)) {
                $sql = "SELECT subject_id, subject_name, course_id FROM Subject WHERE subject_id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "i", $subject_id_to_edit);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    if (mysqli_num_rows($result) > 0) {
                        $subject_data = mysqli_fetch_assoc($result);
                    } else {
                        $message = "<p style='color:red;'>Subject not found for editing.</p>";
                    }
                    mysqli_stmt_close($stmt);
                }
            }
            ?>
            <h2>Update Subject Details</h2>
            <form method="post" action="">
                <input type="hidden" name="form_type" value="update_subject">
                <label for="update_subject_id">Subject ID:</label>
                <input type="text" name="subject_id" id="update_subject_id" value="<?php echo ($subject_data['subject_id'] ?? ''); ?>" readonly required><br>
                <label for="update_subject_name">Subject Name:</label>
                <input type="text" name="subject_name" id="update_subject_name" value="<?php echo htmlspecialchars($subject_data['subject_name'] ?? ''); ?>" required><br>
                <label for="update_subject_course">Course:</label>
                <select name="course_id" id="update_subject_course" required>
                    <option value="">-- Select Course --</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?php echo $course['course_id']; ?>" <?php echo (isset($subject_data['course_id']) && $subject_data['course_id'] == $course['course_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($course['course_code'] . ' - ' . $course['course_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select><br><br>
                <input type="submit" value="Update Subject">
            </form>
            <?php
            break;

        case 'delete_subject_form':
            ?>
            <h2>Delete Subject</h2>
            <form method="post" action="">
                <input type="hidden" name="form_type" value="delete_subject">
                <label for="delete_subject_id">Subject ID to Delete:</label>
                <input type="text" name="subject_id" id="delete_subject_id" required><br><br>
                <input type="submit" value="Delete Subject">
            </form>
            <?php
            break;

        // --- Exam Views ---
        case 'view_all_exams':
            echo "<h2>All Exams</h2>";
            echo "<p><a href='?action=create_exam_form'>Add New Exam</a></p>";
            $sql = "SELECT E.exam_id, E.exam_name, S.subject_name, C.course_code FROM Exam E JOIN Subject S ON E.subject_id = S.subject_id JOIN Course C ON S.course_id = C.course_id";
            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                echo "<table>";
                echo "<thead><tr><th>ID</th><th>Exam Name</th><th>Subject</th><th>Course Code</th><th>Actions</th></tr></thead>";
                echo "<tbody>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['exam_id'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['exam_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['subject_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['course_code']) . "</td>";
                    echo "<td>";
                    echo "<a href='?action=update_exam_form&id=" . $row['exam_id'] . "'>Edit</a> ";
                    echo "<form method='post' action='?action=delete_exam' style='display:inline-block;'>";
                    echo "<input type='hidden' name='form_type' value='delete_exam'>";
                    echo "<input type='hidden' name='exam_id' value='" . $row['exam_id'] . "'>";
                    echo "<button type='submit'>Delete</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>No exams found.</p>";
            }
            break;

        case 'create_exam_form':
            $subjects = [];
            $sql_subjects = "SELECT subject_id, subject_name, C.course_code FROM Subject S JOIN Course C ON S.course_id = C.course_id ORDER BY C.course_code, subject_name";
            $result_subjects = mysqli_query($conn, $sql_subjects);
            if ($result_subjects) {
                while ($row = mysqli_fetch_assoc($result_subjects)) {
                    $subjects[] = $row;
                }
            }
            ?>
            <h2>Add New Exam</h2>
            <form method="post" action="">
                <input type="hidden" name="form_type" value="create_exam">
                <label for="exam_name">Exam Name:</label>
                <input type="text" name="exam_name" id="exam_name" required><br>
                <label for="exam_subject">Subject:</label>
                <select name="subject_id" id="exam_subject" required>
                    <option value="">-- Select Subject --</option>
                    <?php foreach ($subjects as $subject): ?>
                        <option value="<?php echo $subject['subject_id']; ?>">
                            <?php echo htmlspecialchars($subject['subject_name'] . ' (' . $subject['course_code'] . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select><br><br>
                <input type="submit" value="Add Exam">
            </form>
            <?php
            break;

        case 'update_exam_form':
            $exam_id_to_edit = isset($_GET['id']) ? sanitize_input($_GET['id']) : '';
            $exam_data = [];
            $subjects = []; // For dropdown

            $sql_subjects = "SELECT subject_id, subject_name, C.course_code FROM Subject S JOIN Course C ON S.course_id = C.course_id ORDER BY C.course_code, subject_name";
            $result_subjects = mysqli_query($conn, $sql_subjects);
            if ($result_subjects) {
                while ($row = mysqli_fetch_assoc($result_subjects)) {
                    $subjects[] = $row;
                }
            }

            if (!empty($exam_id_to_edit) && is_numeric($exam_id_to_edit)) {
                $sql = "SELECT exam_id, exam_name, subject_id FROM Exam WHERE exam_id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "i", $exam_id_to_edit);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    if (mysqli_num_rows($result) > 0) {
                        $exam_data = mysqli_fetch_assoc($result);
                    } else {
                        $message = "<p style='color:red;'>Exam not found for editing.</p>";
                    }
                    mysqli_stmt_close($stmt);
                }
            }
            ?>
            <h2>Update Exam Details</h2>
            <form method="post" action="">
                <input type="hidden" name="form_type" value="update_exam">
                <label for="update_exam_id">Exam ID:</label>
                <input type="text" name="exam_id" id="update_exam_id" value="<?php echo ($exam_data['exam_id'] ?? ''); ?>" readonly required><br>
                <label for="update_exam_name">Exam Name:</label>
                <input type="text" name="exam_name" id="update_exam_name" value="<?php echo htmlspecialchars($exam_data['exam_name'] ?? ''); ?>" required><br>
                <label for="update_exam_subject">Subject:</label>
                <select name="subject_id" id="update_exam_subject" required>
                    <option value="">-- Select Subject --</option>
                    <?php foreach ($subjects as $subject): ?>
                        <option value="<?php echo $subject['subject_id']; ?>" <?php echo (isset($exam_data['subject_id']) && $exam_data['subject_id'] == $subject['subject_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($subject['subject_name'] . ' (' . $subject['course_code'] . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select><br><br>
                <input type="submit" value="Update Exam">
            </form>
            <?php
            break;

        case 'delete_exam_form':
            ?>
            <h2>Delete Exam</h2>
            <form method="post" action="">
                <input type="hidden" name="form_type" value="delete_exam">
                <label for="delete_exam_id">Exam ID to Delete:</label>
                <input type="text" name="exam_id" id="delete_exam_id" required><br><br>
                <input type="submit" value="Delete Exam">
            </form>
            <?php
            break;

        // --- Enrollment Views ---
        case 'view_all_enrollments':
            echo "<h2>All Enrollments</h2>";
            echo "<p><a href='?action=enroll_student_form'>Enroll New Student</a></p>";
            $sql = "SELECT E.enrollment_id, S.name AS student_name, S.surname AS student_surname, C.course_code, C.course_name, E.enrollment_date, E.final_grade_for_course, E.status FROM Enrollment E JOIN Student S ON E.student_id = S.student_id JOIN Course C ON E.course_id = C.course_id ORDER BY E.enrollment_date DESC";
            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                echo "<table>";
                echo "<thead><tr><th>ID</th><th>Student</th><th>Course</th><th>Date</th><th>Grade</th><th>Status</th><th>Actions</th></tr></thead>";
                echo "<tbody>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['enrollment_id'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['student_name'] . ' ' . $row['student_surname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['course_code'] . ' - ' . $row['course_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['enrollment_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['final_grade_for_course'] ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                    echo "<td>";
                    echo "<a href='?action=update_enrollment_form&id=" . $row['enrollment_id'] . "'>Edit</a> ";
                    echo "<form method='post' action='?action=delete_enrollment' style='display:inline-block;'>";
                    echo "<input type='hidden' name='form_type' value='delete_enrollment'>";
                    echo "<input type='hidden' name='enrollment_id' value='" . $row['enrollment_id'] . "'>";
                    echo "<button type='submit'>Delete</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>No enrollments found.</p>";
            }
            break;

        case 'enroll_student_form':
            $students = [];
            $courses = [];

            $sql_students_dd = "SELECT student_id, name, surname FROM Student ORDER BY name";
            $result_students_dd = mysqli_query($conn, $sql_students_dd);
            if ($result_students_dd) {
                while ($row = mysqli_fetch_assoc($result_students_dd)) {
                    $students[] = $row;
                }
            }

            $sql_courses_dd = "SELECT course_id, course_code, course_name FROM Course ORDER BY course_code";
            $result_courses_dd = mysqli_query($conn, $sql_courses_dd);
            if ($result_courses_dd) {
                while ($row = mysqli_fetch_assoc($result_courses_dd)) {
                    $courses[] = $row;
                }
            }
            ?>
            <h2>Enroll Student in a Course</h2>
            <form method="post" action="">
                <input type="hidden" name="form_type" value="enroll_student">
                <label for="enroll_student_id">Select Student:</label>
                <select name="student_id" id="enroll_student_id" required>
                    <option value="">-- Select Student --</option>
                    <?php foreach ($students as $student): ?>
                        <option value="<?php echo $student['student_id']; ?>">
                            <?php echo htmlspecialchars($student['name'] . ' ' . $student['surname'] . ' (ID: ' . $student['student_id'] . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select><br>

                <label for="enroll_course_id">Select Course:</label>
                <select name="course_id" id="enroll_course_id" required>
                    <option value="">-- Select Course --</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?php echo $course['course_id']; ?>">
                            <?php echo htmlspecialchars($course['course_code'] . ' - ' . $course['course_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select><br><br>
                <input type="submit" value="Enroll Student">
            </form>
            <?php
            break;

        case 'update_enrollment_form':
            $enrollment_id_to_edit = isset($_GET['id']) ? sanitize_input($_GET['id']) : '';
            $enrollment_data = [];

            if (!empty($enrollment_id_to_edit) && is_numeric($enrollment_id_to_edit)) {
                $sql = "SELECT E.enrollment_id, S.name AS student_name, S.surname AS student_surname, C.course_code, C.course_name, E.enrollment_date, E.final_grade_for_course, E.status FROM Enrollment E JOIN Student S ON E.student_id = S.student_id JOIN Course C ON E.course_id = C.course_id WHERE E.enrollment_id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "i", $enrollment_id_to_edit);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    if (mysqli_num_rows($result) > 0) {
                        $enrollment_data = mysqli_fetch_assoc($result);
                    } else {
                        $message = "<p style='color:red;'>Enrollment not found for editing.</p>";
                    }
                    mysqli_stmt_close($stmt);
                }
            }
            ?>
            <h2>Update Enrollment Details</h2>
            <form method="post" action="">
                <input type="hidden" name="form_type" value="update_enrollment">
                <label for="update_enrollment_id">Enrollment ID:</label>
                <input type="text" name="enrollment_id" id="update_enrollment_id" value="<?php echo ($enrollment_data['enrollment_id'] ?? ''); ?>" readonly required><br>
                <label>Student:</label>
                <input type="text" value="<?php echo htmlspecialchars(($enrollment_data['student_name'] ?? '') . ' ' . ($enrollment_data['student_surname'] ?? '')); ?>" readonly><br>
                <label>Course:</label>
                <input type="text" value="<?php echo htmlspecialchars(($enrollment_data['course_code'] ?? '') . ' - ' . ($enrollment_data['course_name'] ?? '')); ?>" readonly><br>
                <label for="update_enrollment_date">Enrollment Date:</label>
                <input type="text" name="enrollment_date" id="update_enrollment_date" value="<?php echo htmlspecialchars($enrollment_data['enrollment_date'] ?? ''); ?>" readonly><br>
                <label for="update_final_grade">Final Grade:</label>
                <input type="text" name="final_grade_for_course" id="update_final_grade" value="<?php echo htmlspecialchars($enrollment_data['final_grade_for_course'] ?? ''); ?>"><br>
                <label for="update_status">Status:</label>
                <select name="status" id="update_status">
                    <option value="Enrolled" <?php echo (isset($enrollment_data['status']) && $enrollment_data['status'] == 'Enrolled') ? 'selected' : ''; ?>>Enrolled</option>
                    <option value="Completed" <?php echo (isset($enrollment_data['status']) && $enrollment_data['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                    <option value="Withdrawn" <?php echo (isset($enrollment_data['status']) && $enrollment_data['status'] == 'Withdrawn') ? 'selected' : ''; ?>>Withdrawn</option>
                </select><br><br>
                <input type="submit" value="Update Enrollment">
            </form>
            <?php
            break;

        case 'delete_enrollment_form':
            ?>
            <h2>Delete Enrollment</h2>
            <form method="post" action="">
                <input type="hidden" name="form_type" value="delete_enrollment">
                <label for="delete_enrollment_id">Enrollment ID to Delete:</label>
                <input type="text" name="enrollment_id" id="delete_enrollment_id" required><br><br>
                <input type="submit" value="Delete Enrollment">
            </form>
            <?php
            break;

        // --- StudentExam Views ---
        case 'view_all_student_exams':
            echo "<h2>All Exam Scores</h2>";
            echo "<p><a href='?action=record_exam_score_form'>Record New Score</a></p>";
            $sql = "SELECT SE.student_exam_id, S.name AS student_name, S.surname AS student_surname, E.exam_name, SE.score, SE.letter_grade, SE.exam_date_taken FROM StudentExam SE JOIN Student S ON SE.student_id = S.student_id JOIN Exam E ON SE.exam_id = E.exam_id ORDER BY SE.exam_date_taken DESC";
            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                echo "<table>";
                echo "<thead><tr><th>ID</th><th>Student</th><th>Exam</th><th>Score</th><th>Grade</th><th>Date Taken</th></tr></thead>";
                echo "<tbody>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['student_exam_id'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['student_name'] . ' ' . $row['student_surname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['exam_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['score']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['letter_grade'] ?? 'N/A') . "</td>";
                    echo "<td>" . htmlspecialchars($row['exam_date_taken']) . "</td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>No exam scores found.</p>";
            }
            break;

        case 'record_exam_score_form':
            $students = [];
            $exams = [];

            $sql_students_dd = "SELECT student_id, name, surname FROM Student ORDER BY name";
            $result_students_dd = mysqli_query($conn, $sql_students_dd);
            if ($result_students_dd) {
                while ($row = mysqli_fetch_assoc($result_students_dd)) {
                    $students[] = $row;
                }
            }

            $sql_exams_dd = "SELECT exam_id, exam_name, S.subject_name FROM Exam E JOIN Subject S ON E.subject_id = S.subject_id ORDER BY exam_name";
            $result_exams_dd = mysqli_query($conn, $sql_exams_dd);
            if ($result_exams_dd) {
                while ($row = mysqli_fetch_assoc($result_exams_dd)) {
                    $exams[] = $row;
                }
            }
            ?>
            <h2>Record Exam Score</h2>
            <form method="post" action="">
                <input type="hidden" name="form_type" value="record_exam_score">
                <label for="exam_student_id">Select Student:</label>
                <select name="student_id" id="exam_student_id" required>
                    <option value="">-- Select Student --</option>
                    <?php foreach ($students as $student): ?>
                        <option value="<?php echo $student['student_id']; ?>">
                            <?php echo htmlspecialchars($student['name'] . ' ' . $student['surname'] . ' (ID: ' . $student['student_id'] . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select><br>

                <label for="exam_id">Select Exam:</label>
                <select name="exam_id" id="exam_id" required>
                    <option value="">-- Select Exam --</option>
                    <?php foreach ($exams as $exam): ?>
                        <option value="<?php echo $exam['exam_id']; ?>">
                            <?php echo htmlspecialchars($exam['exam_name'] . ' (' . $exam['subject_name'] . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select><br>

                <label for="score">Score (e.g., 85.5):</label>
                <input type="number" step="0.01" name="score" id="score" required><br>

                <label for="letter_grade">Letter Grade (Optional, e.g., A, B+, C):</label>
                <input type="text" name="letter_grade" id="letter_grade" maxlength="5"><br><br>
                <input type="submit" value="Record Score">
            </form>
            <?php
            break;

        case 'update_student_exam_form':
            $student_exam_id_to_edit = isset($_GET['id']) ? sanitize_input($_GET['id']) : '';
            $student_exam_data = [];

            if (!empty($student_exam_id_to_edit) && is_numeric($student_exam_id_to_edit)) {
                $sql = "SELECT SE.student_exam_id, S.name AS student_name, S.surname AS student_surname, E.exam_name, SE.score, SE.letter_grade, SE.exam_date_taken FROM StudentExam SE JOIN Student S ON SE.student_id = S.student_id JOIN Exam E ON SE.exam_id = E.exam_id WHERE SE.student_exam_id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "i", $student_exam_id_to_edit);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    if (mysqli_num_rows($result) > 0) {
                        $student_exam_data = mysqli_fetch_assoc($result);
                    } else {
                        $message = "<p style='color:red;'>Exam score record not found for editing.</p>";
                    }
                    mysqli_stmt_close($stmt);
                }
            }
            ?>
            <h2>Update Exam Score Details</h2>
            <form method="post" action="">
                <input type="hidden" name="form_type" value="update_student_exam">
                <label for="update_student_exam_id">Exam Score ID:</label>
                <input type="text" name="student_exam_id" id="update_student_exam_id" value="<?php echo ($student_exam_data['student_exam_id'] ?? ''); ?>" readonly required><br>
                <label>Student:</label>
                <input type="text" value="<?php echo htmlspecialchars(($student_exam_data['student_name'] ?? '') . ' ' . ($student_exam_data['student_surname'] ?? '')); ?>" readonly><br>
                <label>Exam:</label>
                <input type="text" value="<?php echo htmlspecialchars($student_exam_data['exam_name'] ?? ''); ?>" readonly><br>
                <label for="update_score">Score:</label>
                <input type="number" step="0.01" name="score" id="update_score" value="<?php echo htmlspecialchars($student_exam_data['score'] ?? ''); ?>" required><br>
                <label for="update_letter_grade">Letter Grade:</label>
                <input type="text" name="letter_grade" id="update_letter_grade" value="<?php echo htmlspecialchars($student_exam_data['letter_grade'] ?? ''); ?>" maxlength="5"><br>
                <label for="update_exam_date_taken">Date Taken:</label>
                <input type="datetime-local" name="exam_date_taken" id="update_exam_date_taken" value="<?php echo htmlspecialchars(str_replace(' ', 'T', $student_exam_data['exam_date_taken'] ?? '')); ?>" required><br><br>
                <input type="submit" value="Update Exam Score">
            </form>
            <?php
            break;

        case 'delete_student_exam_form':
            ?>
            <h2>Delete Exam Score</h2>
            <form method="post" action="">
                <input type="hidden" name="form_type" value="delete_student_exam">
                <label for="delete_student_exam_id">Exam Score ID to Delete:</label>
                <input type="text" name="student_exam_id" id="delete_student_exam_id" required><br><br>
                <input type="submit" value="Delete Exam Score">
            </form>
            <?php
            break;
    }
    ?>
</div>

</body>
</html>

<?php
mysqli_close($conn);
?>
