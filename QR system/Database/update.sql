UPDATE STUDENT
SET email = 'newemail@usa.edu.ph'
WHERE student_id = 1;

UPDATE COURSES
SET course_name = 'ITME 412'
WHERE course_id = 1;

UPDATE ATTENDANCE
SET status = 'Late'
WHERE student_id = 2 AND date = '2024-10-25' AND course_id = 1;

UPDATE COURSES
SET instructor = 'James Bond'
WHERE course_id = 1;

UPDATE ATTENDANCE
SET status = 'Present'
WHERE course_id = 1 AND date = '2024-10-25';