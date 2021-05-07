<?php
    
    class Products{

        //Instansvariabler 
        private static $productsArray;
        private static $categories;
        private static $errors = []; 
        
        //Main metod
        public static function main($data){
            //saving data from passed argument to my instanse variables
            self::$productsArray = $data["products"];
            self::$categories = $data["categories"];

            //Get querry string data to filter by category and amount of products to show
            $filterCategory = self::getCategory(); 
            $show = self::showAmountOfProducts(); 
            
            //outputs Json to browser
            self::outputsJson($show, $filterCategory);
        }
 

        //Validates querry strings
        private static function validator($whatToValidete, $qryStr){

            //validates value of show (range and if not number)
            if($whatToValidete == "show"){
                try{
                    if( $qryStr > 20 || $qryStr < 1 || !is_numeric($qryStr)){
                        throw new Exception("Not a valid number, please enter a number between 1-20" );
                    }
                }
                //pushing error message to array
                catch(Exception $e) {
                    $error = $e->getMessage();
                    array_push(self::$errors, $error);
                }
            }
            
            //checks if asked category exist
            if($whatToValidete === "category"){
                try{
                    if($qryStr && !in_array($qryStr, self::$categories)){
                        throw new Exception("No such category");
                    }

                }
                //pushing error message to array
                catch(Exception $e) {
                    $error = $e->getMessage();
                    array_push(self::$errors, $error);
                  }
            }
        }
        
        //getting amount of products to show,
        private static function showAmountOfProducts(){
            isset($_GET["show"])? $show = $_GET["show"] : $show=20; 
            self::validator("show", $show);
            return $show;
        }

        //Filter categories
        private static function getCategory(){
            isset($_GET["category"])?$category = $_GET["category"]: $category = null;
            self::validator("category" ,$category);
            return $category;
        }

        //Outputs json to the browser
        private static function outputsJson($amountOfProducts, $category){
            //checks if any error, if so render errors and exits
            if(self::$errors){
                echo json_encode(self::$errors);
                exit();
            }
            
            //Creating a shuffled version of product array
            $shuffledProductsArray = self::$productsArray;
            shuffle($shuffledProductsArray);

            //Decides wether to use shuffled or regular array; 
            $amountOfProducts == 20 ? $finalProductArray = self::$productsArray : $finalProductArray = $shuffledProductsArray;
            
            //Filter out none wanted categories
            if($category){
                $finalProductArray = array_filter($finalProductArray, function($data) use($category){
                    return $data["category"] == $category;
                });
            }
            
            //Cutting finalProductArray to decired ammount
            array_splice($finalProductArray,$amountOfProducts);

            //Outputs Json to the browser
            $json = json_encode($finalProductArray, JSON_UNESCAPED_UNICODE);
            echo $json;
        }

    }

