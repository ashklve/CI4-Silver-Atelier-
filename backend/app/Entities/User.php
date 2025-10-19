<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class User extends Entity
{
    protected $datamap = [];
    
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    
    protected $casts   = [
        'id' => 'integer',
        'account_status' => 'boolean',
        'email_activated' => 'boolean',
        'newsletter' => 'boolean'
    ];

    // get full name
    public function getFullName(): string
    {
        $name = trim($this->first_name . ' ');
        
        if (!empty($this->middle_name)) {
            $name .= trim($this->middle_name) . ' ';
        }
        
        $name .= trim($this->last_name);
        
        return $name;
    }

    // get display name 
    public function getDisplayName(): string
    {
        return $this->first_name . ' ' . substr($this->last_name, 0, 1) . '.';
    }

    // check if the user is admin
    public function isAdmin(): bool
    {
        return $this->type === 'admin';
    }

    // check if the user is client
    public function isClient(): bool
    {
        return $this->type === 'client';
    }

    // check if account is active
    public function isActive(): bool
    {
        return $this->account_status === true || $this->account_status === 1;
    }

    // check if the email is verified
    public function isEmailVerified(): bool
    {
        return $this->email_activated === true || $this->email_activated === 1;
    }

    // get the profile image
    public function getProfileImageUrl(): string
    {
        if (!empty($this->profile_image)) {
            return base_url('uploads/profiles/' . $this->profile_image);
        }
        
        // return default avatar
        return base_url('images/default-avatar.png');
    }

    // get formatted address
    public function getFormattedAddress(): string
    {
        $parts = [];
        
        if (!empty($this->address)) {
            $parts[] = $this->address;
        }
        
        if (!empty($this->city)) {
            $parts[] = $this->city;
        }
        
        if (!empty($this->postal_code)) {
            $parts[] = $this->postal_code;
        }
        
        return implode(', ', $parts);
    }
}