<?php
// Écouteur d'événements Doctrine pour historiser les modifications

namespace App\Listener;

use App\Entity\Contact;
use Doctrine\ORM\Event\PostUpdateEventArgs;

//use App\Entity\ContactHistory;

class ContactHistoryListener
{
    public function postUpdate(Contact $contact, PostUpdateEventArgs $event)
    {
        //ToDo : make ContactHistory entity and save the modifications
    }
}