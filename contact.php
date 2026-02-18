<?php
declare(strict_types=1);

function redirectToContact(string $state)
{
    header("Location: /index.html?contact=$state#contact", true, 303);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    redirectToContact('error');
}

$honeypot = trim((string)($_POST['website'] ?? ''));
if ($honeypot !== '') {
    redirectToContact('spam');
}

$name = trim((string)($_POST['nom'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));
$vehicle = trim((string)($_POST['vehicule'] ?? ''));
$project = trim((string)($_POST['projet'] ?? ''));

if (
    $name === '' ||
    $vehicle === '' ||
    $project === '' ||
    !filter_var($email, FILTER_VALIDATE_EMAIL)
) {
    redirectToContact('invalid');
}

if (
    strlen($name) > 120 ||
    strlen($email) > 180 ||
    strlen($vehicle) > 180 ||
    strlen($project) > 5000
) {
    redirectToContact('invalid');
}

$safeName = str_replace(["\r", "\n"], ' ', $name);
$safeEmail = str_replace(["\r", "\n"], '', $email);

$subject = 'Nouvelle demande devis - La Main du Sellier';
$to = 'contact@lamaindusellier.fr';

$ip = (string)($_SERVER['REMOTE_ADDR'] ?? 'inconnue');
$userAgent = (string)($_SERVER['HTTP_USER_AGENT'] ?? 'inconnu');
$submittedAt = (new DateTimeImmutable('now', new DateTimeZone('Europe/Paris')))->format('Y-m-d H:i:s');

$message = implode("\n", [
    'Nouvelle demande depuis lamaindusellier.fr',
    '',
    "Nom: $name",
    "Email: $email",
    "Vehicule: $vehicle",
    "Projet:",
    $project,
    '',
    "Date (Europe/Paris): $submittedAt",
    "IP: $ip",
    "User-Agent: $userAgent",
]);

$headers = [
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8',
    'From: La Main du Sellier <no-reply@lamaindusellier.fr>',
    "Reply-To: $safeName <$safeEmail>",
];

$isSent = mail($to, $subject, $message, implode("\r\n", $headers));
redirectToContact($isSent ? 'success' : 'error');
