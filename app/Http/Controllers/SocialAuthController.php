<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use Auth;
use Hash;
use App\User;

class SocialAuthController extends Controller
{
   
    /**
     * Função para redirecionar para o login do github
     * @return type
     */
    public function entrarGithub()
    {
        return Socialite::driver('github')->redirect();
    }
    
    /**
     * Função para tratar o retorno da API do githhub
     * @return type
     */
    public function retornoGithub() 
    {
        try {
              
            $userSocial = Socialite::driver('github')->user();
            $email = $userSocial->getEmail();

            //verifica se o usuario já está logado no sistema
            if(Auth::check()){
                $user = Auth::user();
                $user->email_github = $email;
                $user->save();
                return redirect()->intended('/home');
            }else{
                //Verifica se na base já possui algum usuario cadastrado com o e-mail
                $user = User::where('email_github',$email)->first();

                if(isset($user->name)){
                  Auth::login($user);
                  return redirect()->intended('/home');
                }else{
                    //Verifica se na base já possui algum usuario cadastrado com o e-mail
                    $user = User::where('email',$email)->first();
                    if(isset($user->name)){
                      $user->email_github = $email;
                      $user->save();
                      Auth::login($user);
                      return redirect()->intended('/home');
                    }else{
                        //se não existir usuario na base cadastra
                        $this->novoUsuarioSocial($userSocial, 'github');
                        Auth::login($user);
                        return redirect()->intended('/home');
                    }
                }  
            }
        } catch (\Exception $ex) {
            echo 'ERRO AO REALIZAAR LOGIN COM GITHUB';
        } 
    }
    
    
    /**
     * Função para redirecionar para o login do facebook
     * @return type
     */
    public function entrarFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }
    
    /**
     * Função para tratar o retorno da API do facebook
     * @return type
     */
    public function retornoFacebook() 
    {
        try {
              
            $userSocial = Socialite::driver('facebook')->user();
            $email = $userSocial->getEmail();

            //verifica se o usuario já está logado no sistema
            if(Auth::check()){
                $user = Auth::user();
                $user->email_facebook = $email;
                $user->save();
                return redirect()->intended('/home');
            }else{
                //Verifica se na base já possui algum usuario cadastrado com o e-mail
                $user = User::where('email_facebook',$email)->first();

                if(isset($user->name)){
                  Auth::login($user);
                  return redirect()->intended('/home');
                }else{
                    //Verifica se na base já possui algum usuario cadastrado com o e-mail
                    $user = User::where('email',$email)->first();
                    if(isset($user->name)){
                      $user->email_facebook = $email;
                      $user->save();
                      Auth::login($user);
                      return redirect()->intended('/home');
                    }else{
                        //se não existir usuario na base cadastra
                        $this->novoUsuarioSocial($userSocial, 'facebook');
                        
                        Auth::login($user);
                        return redirect()->intended('/home');
                    }
                }  
            }
        } catch (\Exception $ex) {
            echo 'ERRO AO REALIZAAR LOGIN COM FACEBOOK';
        } 
    }
    
    /**
     * cadastra um novo usuario de acordo com o tipo
     * @param type $userSocial
     * @param type $tipo
     */
    private function novoUsuarioSocial($userSocial, $tipo){
        $user = new User;
        $user->name = $userSocial->getName();
        $user->email = $userSocial->getEmail();
        if($tipo == 'github'){
            $user->email_github = $userSocial->getEmail();
        }else if ($tipo == 'facebook'){
            $user->email_facebook = $userSocial->getEmail();
        }
        $user->password = Hash::make($userSocial->token);
        $user->save();
    }
}