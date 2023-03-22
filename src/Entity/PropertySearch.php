<?php
namespace App\Entity;
class PropertySearch
{
 private $name;
 public function getname(): ?string
 {
 return $this->name;
 }
 public function setname(string $name): self
 {
 $this->name = $name;
 return $this;
 }
}
