<?php

namespace App\Entity;

class Product
{
    private $id;
    private $name;
    private $category;
    private $description;
    private $shortDesc;
    private $price;
    private $link;
    private $image;
    private $brand;
    private $rating;
    private $caffeineType;
    private $count;
    private $flavored;
    private $seasonal;
    private $inStock;
    private $facebook;
    private $isKCup;


    //Define the Getters
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function getSku()
    {
        return $this->sku;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getShortDesc()
    {
        return $this->shortDesc;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function getBrand()
    {
        return $this->brand;
    }

    public function getRating()
    {
        return $this->rating;
    }

    public function getCaffeineType()
    {
        return $this->caffeineType;
    }

    public function getCount()
    {
        return $this->count;
    }

    public function getFlavored()
    {
        return $this->flavored;
    }

    public function getSeasonal()
    {
        return $this->seasonal;
    }

    public function getInStock()
    {
        return $this->inStock;
    }

    public function getFacebook()
    {
        return $this->facebook;
    }

    public function getIsKCup()
    {
        return $this->isKCup;
    }

    // Define the Setters
    public function setName($name)
    {
        $this->name = $name;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setShortDesc($shortDesc)
    {
        $this->shortDesc = $shortDesc;
    }

    public function setPrice($price)
    {
        $this->price =  $price;
    }

    public function setLink($link)
    {
        $this->link = $link;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function setBrand($brand)
    {
        $this->brand =  $brand;
    }

    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    public function setCaffeineType($caffeineType)
    {
        $this->caffeineType = $caffeineType;
    }

    public function setCount($count)
    {
        $this->count = $count;
    }

    public function setFlavored($flavored)
    {
        $this->flavored = $flavored;
    }

    public function setSeasonal($seasonal)
    {
        $this->seasonal =  $seasonal;
    }

    public function setInStock($inStock)
    {
        $this->inStock =  $inStock;
    }

    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;
    }

    public function setIsKCup($isKCup)
    {
        $this->isKCup = $isKCup;
    }
}
