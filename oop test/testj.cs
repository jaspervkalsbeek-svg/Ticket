class Student
{
    string Name;
    int Age;
    string gender;
    bool IsEnrolled;
}

student_1 = new Student();
student_2 = new Student();

void printstudent(Student student)
{
    Console.WriteLine($"Name: {student.Name}");
    Console.WriteLine($"Age: {student.Age}");
    Console.WriteLine($"Gender: {student.gender}");
    Console.WriteLine($"Is Enrolled: {student.IsEnrolled}");
}