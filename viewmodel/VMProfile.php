<?php

namespace ViewModel;

use Model\Position;
use Model\Role;
use Model\Staff;
use Model\User;

require_once ("../model/Role.php");
require_once ("../model/Position.php");
require_once ("../model/User.php");
require_once ("../model/Staff.php");

class VMProfile
{
    public static function getRoles() : int | false
    {
        $out = [];
        foreach (Role::findAll() as $role){
            $out[] = $role->toData();
        }
        echo json_encode($out);
        return (count($out)!=0)?count($out):false;
    }
    #todo fix later maybe... (i won't)
    public static function addRole(array $data) : Role | bool
    {
        Product::insert($data["name"]);
        return true;
    }
    public static function changeRole(Role $role) : bool
    {
        if (!Role::findById($role->getId())){
            return false;
        }
        Role::update($role);
        return true;
    }

    public static function getPositions() : int | false
    {
        $out = [];
        foreach (Position::findAll() as $position){
            $out[] = $position->toData();
        }
        echo json_encode($out);
        return (count($out)!=0)?count($out):false;
    }
    public static function addPosition(array $data) : Position | bool
    {
        Position::insert($data["name"]);
        return true;
    }
    public static function changePosition(Position $position) : bool
    {
        if (!Position::findById($position->getId())){
            return false;
        }
        Position::update($position);
        return true;
    }
    public static function getUsers() : int | false
    {
        $out = [];
        foreach (User::findAll() as $user){
            $out[] = $user->toData();
        }
        echo json_encode($out);
        return (count($out)!=0)?count($out):false;
    }
    public static function getUser(int $id) : User | false
    {
        $user = User::findById($id);
        if ($user){
            $out = $user->toData();
            echo json_encode($out);
        }
        return $user;
    }
    public static function addUser(array $data) : User | bool
    {
        User::insert($data["login"], $data["password"], Role::dataToRole($data["role_id"]));
        return true;
    }
    public static function changeUser(User $user) : bool
    {
        if (!User::findById($user->getId())){
            return false;
        }
        User::update($user);
        return true;
    }
    public static function getStaff(bool $noUserData = true) : int | false
    {
        $out = [];
        foreach (Staff::findAll() as $staff){
            $out[] = $staff->toData($noUserData);
        }
        echo json_encode($out);
        return (count($out)!=0)?count($out):false;
    }
    public static function getStaffMember(int $id, $noUserData = true) : Staff | false
    {
        $staff = Staff::findById($id);
        if ($staff){
            $out = $staff->toData($noUserData);
            echo json_encode($out);
        }
        return $staff;
    }
    public static function addStaff(array $data) : User | bool
    {
        User::insert($data["login"], $data["password"], Role::dataToRole($data["role_id"]));
        return true;
    }
    public static function changeStaff(Staff $staff) : bool
    {
        if (!Staff::findById($staff->getId())){
            return false;
        }
        Staff::update($staff);
        return true;
    }
}

#VMProfile::getStaff();