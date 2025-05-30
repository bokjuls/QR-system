SELECT * FROM STUDENT;

SELECT * FROM COURSES;

SELECT * FROM ATTENDANCE;

SELECT 
    A.attendance_id,
    S.first_name,
    S.last_name,
    C.course_name,
    A.date,
    A.status
FROM 
    ATTENDANCE A
JOIN 
    STUDENT S ON A.student_id = S.student_id
JOIN 
    COURSES C ON A.course_id = C.course_id;


SELECT 
    S.first_name,
    S.last_name,
    C.course_name,
    A.date,
    A.status
FROM 
    ATTENDANCE A
JOIN 
    STUDENT S ON A.student_id = S.student_id
JOIN 
    COURSES C ON A.course_id = C.course_id
WHERE 
    A.status = 'Absent' AND 
    C.course_name = 'ITME 411' AND 
    A.date = '2024-10-25';


SELECT 
    S.first_name,
    S.last_name,
    C.course_name,
    COUNT(*) AS attendance_count
FROM 
    ATTENDANCE A
JOIN 
    STUDENT S ON A.student_id = S.student_id
JOIN 
    COURSES C ON A.course_id = C.course_id
WHERE 
    A.status = 'Present'
GROUP BY 
    S.student_id, C.course_id;