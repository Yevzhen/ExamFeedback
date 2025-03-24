# ExamFeedback
Web application for students to leave feedback on exam questions

## Overview

This is a secure PHP-based web application that allows students to submit feedback on exam questions. It ensures data integrity and protection against security vulnerabilities such as SQL Injection and Cross-Site Scripting (XSS) using prepared statements and output sanitization.

## Features

<b>User Authentication:</b> Only logged-in users can submit feedback.

<b>Secure Data Handling:</b> Uses htmlspecialchars() for XSS protection and prepared statements for SQL queries.

<b>Feedback Options:</b> Students can choose predefined feedback types and add a narrative.

<b>Dynamic Form Handling:</b> Exam and user details are dynamically displayed.

<b>Error & Success Messages:</b> Provides feedback on submission status.

## Technologies Used

<b>Backend:</b> PHP, MySQL

<b>Frontend:</b> HTML

<b>Security:</b> Prepared Statements to prevent SQL injection, htmlspecialchars() for XSS protection, CSRF token to prevent Cross-Site Request Forgery.

## Installation

1. Clone the repository.

2. Navigate to the project folder.

3. Install dependencies using Composer.

4. Set up the database.

5. Start a local development server.

## Usage

1. Sign Up / Log In as a student.

2. Select an exam question's number and provide feedback.

3. Submit the form to save feedback.

4. Receive confirmation message on successful submission.

5. Logout when finished.
