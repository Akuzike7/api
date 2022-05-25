<?php

namespace controllers;

use core\exceptions\NotFoundException;
use core\Request;
use Exception;
use models\User;
use core\Token;
use models\Verification;
use core\PHPMailer\src\PHPMailer;
use core\PHPMailer\src\SMTP;
use Error;

class AuthController
{
    public function login(Request $request)
    {

        $uname = $request->raw()->uname;
        $password = $request->raw()->password;

        $uname = htmlspecialchars(trim(stripcslashes($uname)));
        $password = htmlspecialchars(trim(stripcslashes($password)));

        $user = new User;

        if (filter_var($uname, FILTER_VALIDATE_EMAIL)) {
            $user = $user->find([
                "email" => $uname
            ]);
        } else {
            $user = $user->find([
                "username" => $uname
            ]);
        }

        if ($user) {
            if (password_verify($password, $user->password)) {
                $payload = [
                    "id" => $user->id,
                    "email" => $user->email,
                    "role" => $user->role_id
                ];
                $AccessToken = Token::generate_access_token($payload);
                $RefreshToken = Token::generate_refresh_token($payload);

                return $res = [
                    'AccessToken' => $AccessToken,
                    'RefreshToken' => $RefreshToken,
                    'user' => [
                        'id' => $user->id,
                        'role_id' => $user->role_id
                    ],
                    'status' => '200'
                ];
            } else {
                return $error = [
                    'message' => 'Bad Creditionals',
                    'status' => '404'
                ];
            }
        } else {
            return $error = [
                'message' => 'Bad Creditionals',
                'status' => '404'
            ];
        }
    }

    public function register(Request $request)
    {
        $code = bin2hex(random_bytes(2));
        $code = strtoupper($code);
        $timezone = date("e");

        return $res = [
            'code' => $code
        ];
    }

    public function forgotPassword(Request $request)
    {
        //getting user from database
        $uname = $request->raw()->uname;
        $uname = htmlspecialchars(trim(stripcslashes($uname)));

        $user = new User;

        if (filter_var($uname, FILTER_VALIDATE_EMAIL)) {
            $user = $user->find([
                "email" => $uname
            ]);
        } else {
            $user = $user->find([
                "username" => $uname
            ]);
        }


        //authenticating creditionals

        if ($user) {
            try {
                $verification = new Verification;

                $code = bin2hex(random_bytes(2));
                $code = strtoupper($code);
                $verification->hashedCode = password_hash($code, PASSWORD_DEFAULT);
                $verification->status = "Active";

                $mail = new PHPMailer(true);

                //$mail->SMTPDebug = SMTP::DEBUG_SERVER; 
                $mail->isSMTP();
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = "SSL";

                $mail->Username = 'testingqwikt@gmail.com';
                $mail->Password = "Password?8";
                $mail->Host = 'smtp.gmail.com';
                $mail->port = '465';

                $mail->setFrom('testingqwikt@gmail.com', 'Maintenance-Portal');
                $mail->isHTML(true);
                $mail->Subject = "Reset Password";
                $mail->Body = "Verification code: $code";
                $mail->addAddress($user->email);


                $mail->send();

                $verification->insert([
                    'email' => $user->email,
                    'hashed_code' => $verification->hashedCode,
                    'status' => $verification->status
                ]);

                return $res = [
                    'message' => 'Verification code sent',
                    'status' => '200'
                ];
            } catch (Exception $e) {
                return $res = [
                    'message' => $e->getMessage(),
                    'status' => '400',
                ];
            }
        } else {
            return $res = [
                'message' => 'Verification code not sent',
                'status' => '400',
            ];
        }
    }

    public function verifyCode(Request $request)
    {
        $uname = $request->raw()->uname;
        $code = $request->raw()->code;

        if (empty($uname) || empty($code)) {
            return $res = [
                'message' => 'empty fields',
                'status' => '400'
            ];
        }

        $uname = htmlspecialchars(trim(stripcslashes($uname)));
        $code = htmlspecialchars(trim(stripcslashes($code)));

        $codes = new Verification;

        $codes = $codes->find([
            'email' => $uname,
            'status' => 'Active'
        ]);

        if (password_verify($code, $codes->hashed_code)) {
            return $res = [
                'message' => "authenticated",
                'status' => '200'
            ];
        } else {
            return $res = [
                'message' => "Incorrect code",
                'status' => '400'
            ];
        }
    }

    public function validateToken(Request $request)
    {
        try {
            $token = new Token;

            $payload = $token::validate_access_token();

            return $payload;
        } catch (Exception $e) {
            return  $res = [
                'message' => "Invalid token",
                'status' => 400,
                'exception' => $e->getMessage()
            ];
        }
    }

    public function validate(Request $request)
    {

        $user = new User;

        $payload = Token::validate_refresh_token();

        throw new NotFoundException('No data tu');

        if(!$user->find(['id' => $payload->user->id])) throw new NotFoundException();

        

    }

    public function reset(Request $request)
    {
        $uname = $request->raw()->uname;
        $password = $request->raw()->password;

        if (empty($uname) || empty($password)) {
            return $res = [
                'message' => 'empty fields',
                'status' => '400'
            ];
        }


        $uname = htmlspecialchars(trim(stripcslashes($uname)));
        $password = htmlspecialchars(trim(stripcslashes($password)));

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $user = new User;


        if (filter_var($uname, FILTER_VALIDATE_EMAIL)) {
            $user = $user->find([
                "email" => $uname
            ]);

            if ($user) {
                $id = $user->id;
                $user = new User;

                $user = $user->update([
                    'password' => $hashedPassword
                ], [
                    'id' => $id
                ]);

                return $user;
            }
        } else {
            $user = $user->find([
                "username" => $uname
            ]);

            if ($user) {
                $id = $user->id;
                $user = new User;

                $user = $user->update([
                    'password' => $hashedPassword
                ], [
                    'id' => $id
                ]);

                return $user;
            }
        }
    }
}
