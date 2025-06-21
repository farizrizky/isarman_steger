<?php
namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserHelper {

   public static function getUser(){
        $user = Auth::user();
        if(is_null($user)){
            return null;
        }

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->getRoleNames()->first(),
            'fullname' => $user->fullname,
            'phone' => $user->phone,
        ];
   }

   public static function userHasPermission(Array $permission){
        $user = Auth::user();
        if(is_null($user)){
            return false;
        }

        $userPermissions = $user->getAllPermissions()->pluck('name')->toArray();
        if(empty(array_diff($permission, $userPermissions))){
            return true;
        }else {
            return false;
        }
   }

   public static function ownerExists(){
        $owner = User::role('Owner')->exists();
        if($owner){
            return true;
        } else {
            return false;
        }
   }

   public static function direkturExists(){
        $direktur = User::role('Direktur')->exists();
        if($direktur){
            return true;
        } else {
            return false;
        }
    }

   public static function userSigned($userLevel = null){
        if($userLevel == 'Owner'){
            $ownerExists = self::ownerExists();
            if($ownerExists){
                return [
                    'level' => 'Owner',
                    'fullname' => User::role('Owner')->first()['fullname']
                ];
            } else {
                return [
                    'level' => '..............',
                    'fullname' => '........................'
                ];
            }
        }else if($userLevel == 'Direktur'){
            $direkturExists = self::direkturExists();
            if($direkturExists){
                return [
                    'level' => 'Direktur',
                    'fullname' => User::role('Direktur')->first()['fullname']
                ];
            } else {
                return [
                    'level' => '..............',
                    'fullname' => '........................'
                ];
            }
        }else if($userLevel == 'LoggedIn User'){
            return [
                'level' => self::getUser()['role'],
                'fullname' => self::getUser()['fullname']
            ];
        }else{
            return [
                'level' => '...............',
                'fullname' => '........................'
            ];
        }
   }

   public static function userCanApproveRent(){
        $user = User::permission('approve_rent')->get();
        $canApprove = [];
        foreach($user as $u){
            $canApprove[] = [
                'id' => $u->id,
                'fullname' => $u->fullname,
                'phone' => $u->phone,
                'email' => $u->email,
                'role' => $u->getRoleNames()->first()
            ];
        }
        return $canApprove;        
   }
   
    
}