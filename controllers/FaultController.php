<?php

namespace controllers;

use core\Request;
use Exception;
use models\Fault;
use models\FaultTechnician;

class FaultController{
    public function reportFault(Request $request)
    {
        try{
            $attributes = [];
            $data = $request->raw();
            $data = is_object($data) ? (array)$data : $data;
            $token = null;
    
            foreach($data as $key => $value){
                if($key == 'AccessToken'){
                    $token = $value;
                }
                $attributes[$key] = htmlspecialchars(trim(stripcslashes($value)));
            }
    
            $fault = new Fault;
    
            $res = $fault->insert($attributes);
    
            return $res;

        }
        catch(Exception $e){
            return $res = [
                'message' => $e->getMessage(),
                'status' => '404'
            ];
        }

    }
    public function updateFault(Request $request)
    {
        
        try{
            $attributes = [];
            $condition = [];
            $data = $request->raw();
            $data = is_object($data) ? (array)$data : $data;
            $token = null;
    
            foreach($data as $key => $value){
                if($key == 'AccessToken'){
                    $token = $value;
                }
                if($key == 'id'){
                    $condition[$key] = htmlspecialchars(trim(stripcslashes($value)));
                }else{
                    $attributes[$key] = htmlspecialchars(trim(stripcslashes($value)));
                }
            }

            $fault = new Fault;
            $res = $fault->update($attributes,$condition);

            return $res;

        }catch(Exception $e){
            return $res = [
                'message' => $e->getMessage(),
                'status' => '404'
            ];
        }

    }
    public function assignTech(Request $request)
    {
        try{
            $attributes = [];
            $data = $request->raw();
            $data = is_object($data) ? (array)$data : $data;
            $token = null;
    
            foreach($data as $key => $value){    
                if($key == 'AccessToken'){
                    $token = $value;
                }
                $attributes[$key] = htmlspecialchars(trim(stripcslashes($value)));
            }

            $technician = new FaultTechnician;
            $res = $technician->insert($attributes);

            return $res;

        }catch(Exception $e){
            return $res = [
                'message' => $e->getMessage(),
                'status' => '404'
            ];
        }
    }

    public function getFaults(Request $request)
    {
        try{
            $attributes = [];
            $data = $request->params();
            $data = is_object($data) ? (array)$data : $data;

            if($data){ 
                $token = $data[1] ?? null;

                $attributes['user_id'] = htmlspecialchars(trim(stripcslashes($data[0])));
    
                $faults = new Fault;
                $res = $faults->findAll($attributes);
                
                return $res;
            }
            else{
                $faults = new Fault;
                $res = $faults->findAll();
    
                return $res;
            }



            

        }   
        catch(Exception $e){
            return $res = [
                'message' => $e->getMessage(),
                'status' => '404'
            ];
        }  
    
    }


    
}