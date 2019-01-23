<?php

class Email{
    function sendEmail($email, $type){
        //serveur ovh    tout a revoir
        $to    = $email;
        $from  = "contact@book-kevin-guillier.com";  // adresse MAIL OVH liée à l'hébergement.

        $Subject = "Support FindTeamMates";

        $mail_Data = '<p>Bonjour</p>';

        $headers  = "MIME-Version: 1.0 \n";
        $headers .= "Content-type: text/html; charset=iso-8859-1 \n";
        $headers .= "From: $from  \n";
        $headers .= "Reply-To: $email \n";

        // Message de Priorité haute

        $headers .= "X-Priority: 1  \n";
        $headers .= "X-MSMail-Priority: High \n";

        $CR_Mail = TRUE;

        $CR_Mail = @mail ($to, $Subject, $mail_Data, $headers);

        if ($CR_Mail === FALSE){
            return false;
        }
        else{
            include '../Bdd/connexion_user.php';

            include_once 'Ip.php';
            $ipProvider = new Ip();
            $adressTrackingIp = $ipProvider->get_ip();

            include_once 'User.php';
            $userProvider = new User();
            $idUser = $userProvider->getUserByEmail($email);

            try{
                $sql = $bdd->prepare("INSERT INTO tracking_ip(adress_tracking_ip, date_tracking_ip, type_tracking_ip, id_user) VALUES (:ip_client)");
                $sql->bindParam(':adress_tracking_ip', $adressTrackingIp);
                $sql->bindParam(':date_tracking_ip', date("Y-m-d H:i"));
                $sql->bindParam(':type_tracking_ip', $type);
                $sql->bindParam(':id_user', $idUser);
                $sql->execute();

                return true;
            }
            catch (Exception $e){
                $error = $e->getCode();
                $errorMessage = $e->getMessage();

                return false;
            }
        }
    }
}