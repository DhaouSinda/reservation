<?php

class NotificationService
{
    // Coordonnées SMTP à personnaliser pour un envoi réel (ex: Gmail avec mot de passe d'application)
    private const SMTP_HOST = 'smtp.gmail.com';
    private const SMTP_PORT = 587;
    private const SMTP_USERNAME = 'dhaousinda2004@gmail.com';
    private const SMTP_PASSWORD = 'owqe rjpz fpdj xyjv';
    private const EXPEDITEUR_NOM = 'BookIt';

    private static function chargerPHPMailer(): bool
    {
        $autoload = __DIR__ . '/../../vendor/autoload.php';
        if (file_exists($autoload)) {
            require_once $autoload;
            return class_exists('PHPMailer\\PHPMailer\\PHPMailer');
        }
        return false;
    }

    public static function envoyer(string $destinataire, string $nomDestinataire, string $sujet, string $corpsHtml): bool
    {
        if (self::chargerPHPMailer()) {
            return self::envoyerAvecPHPMailer($destinataire, $nomDestinataire, $sujet, $corpsHtml);
        }
        return self::envoyerAvecMailNatif($destinataire, $sujet, $corpsHtml);
    }

    private static function envoyerAvecPHPMailer(string $destinataire, string $nomDestinataire, string $sujet, string $corpsHtml): bool
    {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = self::SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = self::SMTP_USERNAME;
            $mail->Password = self::SMTP_PASSWORD;
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = self::SMTP_PORT;
            $mail->CharSet = 'UTF-8';

            $mail->setFrom(self::SMTP_USERNAME, self::EXPEDITEUR_NOM);
            $mail->addAddress($destinataire, $nomDestinataire);

            $mail->isHTML(true);
            $mail->Subject = $sujet;
            $mail->Body = $corpsHtml;

            $mail->send();
            return true;
        } catch (\Throwable $e) {
            error_log('Erreur envoi email (PHPMailer): ' . $e->getMessage());
            return false;
        }
    }

    private static function envoyerAvecMailNatif(string $destinataire, string $sujet, string $corpsHtml): bool
    {
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: " . self::EXPEDITEUR_NOM . " <no-reply@bookit.local>\r\n";

        try {
            return @mail($destinataire, $sujet, $corpsHtml, $headers);
        } catch (\Throwable $e) {
            error_log('Erreur envoi email (mail natif): ' . $e->getMessage());
            return false;
        }
    }

    public static function confirmationReservation(array $utilisateur, string $dateDebut, string $dateFin, string $salleNom): void
    {
        $sujet = "Confirmation de votre réservation - BookIt";
        $corps = "<h2>Réservation enregistrée</h2>
            <p>Bonjour " . htmlspecialchars($utilisateur['prenom']) . ",</p>
            <p>Votre demande de réservation pour la salle <strong>" . htmlspecialchars($salleNom) . "</strong> a bien été enregistrée.</p>
            <p><strong>Début :</strong> " . htmlspecialchars($dateDebut) . "<br>
               <strong>Fin :</strong> " . htmlspecialchars($dateFin) . "</p>
            <p>Statut : en attente de validation par le gestionnaire.</p>
            <p>— L'équipe BookIt</p>";

        self::envoyer($utilisateur['email'], $utilisateur['prenom'], $sujet, $corps);
    }

    public static function statutReservation(array $utilisateur, string $salleNom, string $statut): void
    {
        $libelle = $statut === 'validee' ? 'validée ✅' : 'refusée ❌';
        $sujet = "Votre réservation a été " . ($statut === 'validee' ? 'validée' : 'refusée') . " - BookIt";
        $corps = "<h2>Mise à jour de votre réservation</h2>
            <p>Bonjour " . htmlspecialchars($utilisateur['prenom']) . ",</p>
            <p>Votre réservation pour la salle <strong>" . htmlspecialchars($salleNom) . "</strong> a été <strong>" . $libelle . "</strong>.</p>
            <p>— L'équipe BookIt</p>";

        self::envoyer($utilisateur['email'], $utilisateur['prenom'], $sujet, $corps);
    }
}
