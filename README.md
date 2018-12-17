# README

Trenutno stanje projekta: [Ovde](https://bitbucket.org/milic22/php-septembar-2017/src)  
Svi prodhodni komitovi: [Ovde](https://bitbucket.org/milic22/php-septembar-2017/commits/)

# Nizovi

## Deklaracija niza
```php
$naziv_niza = ["a", 123, "b", "Milos", false];
```

## Promena vrednosti niza
Promena imena iz Milos u Petar.  
(koristi se indeks 0 je "a", 1 je 123, 2 je "b", 3 je "Milos")
```php
$naziv_niza[3] = "Petar";
```

## Koriscenje for petje za iteraciju kroz niz
Samo ispisuje svaki elemenat niza u novom redu.  
Funkcija `count()` "vraca" broj elemenata u nizu koji joj je proslednjen u zagradi.  
`count($naziv_niza)` bice 5.
```php
for ( $i = 0; $i < count($naziv_niza); $i++ ) {
  echo $naziv_niza[$i];
  echo "<br />";
}
```