@extends('layouts.app')

@section('content')
<div class="container howItWorks">
    <h1>Jak to funguje</h1>
    <div class="how-it-works">
        <section>
            <h2>Registrace a přihlášení</h2>
            <p>Pro zahájení nákupu je nutné se nejprve zaregistrovat a přihlásit. Registrace je jednoduchá a rychlá, stačí vyplnit Vaše údaje a následně Váš účet potvrdit skrze zaslaný mail na vámi vyplněnou mailovou adresu.</p>
        </section>
        <section>
            <h2>Vstup do prodejny</h2>
            <p>Vstup do prodejny je umožněn skrze načtení QR kódu, který je umístěn u vstupu do každé samoobslužné prodejny. Pro jeho načtení klikněte na úvodní straně na tlačítko košíku umístěné ve spodní třetině displeje. Následně klikněte na tlačítko "naskenovat", povolte aplikaci přístup k fotoaparátu a naskenujte QR kód.</p>
            <p>Po načtení QR kódu Vám bude zobrazen přístupový kód od elektronického zámku, který je umístěn na vstupních dvěřích. Po vstupu do prodejny klikněte na tlačítko "Zahájit nákup", nebo počkejte na uplynutí 60 sekund od naskenování QR kódu.</p>
        </section>
        <section>
            <h2>Skenování produktů</h2>
            <p>Naše samoobslužné prodejny umožňují nákup prostřednictvím skenování čárových kódů, které jsou umístěné na každém produktu. V uživatelském rozhraní košíku si můžete naskenovat nový produkt po kliknutí na tlačítko <i class="fas fa-barcode"></i>, to otevře okno s rozhraním čtečky čárových kódu. Nyní pouze stačí namířit fotoaparát na čárový kód zboží a jsou Vám zobrazeny údaje o produktu. V tomto rozhraní můžete navolit, kolik položek chcete přidat do košíku a následně je do košíku přidat.</p>
            <p>Základní rozhraní pro nákup obsahuje košík, který je automaticky přepočítán po změně produktů. Po kliknutí na položku v košíku je možné změnit její počet (jak přidat, odebrat, tak kompletně odstanit z košíku). </p>
            <p>Pokud jste dokončili svůj nákup, stačí kliknout na ikonu <i class="fas fa-credit-card"></i>. Následně budete přesměrování na stránku určenou pro rekapitulaci objednávky. Pro změnu produktů, či pokračování v nákupu můžete kliknout na tlačítko "Zpět do košíku". </p>
            <p>Také můžete objednávku zrušit stisknutím tlačítka "Zrušit objednávku", čímž se ukončí Váš nákup. Následně budete vyzváni k navrácení položek na jejich původní místo a k opuštění obchodu. </p>
            <p>Pokud máte košík překontrolovaný a veškeré údaje odpovídají obsahu vašeho reálného košíku, můžete přejít k platbě stisknutím na tlačítko "Dokončit objednávku". Následně budete přesměrování na platební bránu. </p>
        </section>
        <section>
            <h2>Platba</h2>
            <p>Po přesměrování na stránku platební brány GoPay vyberte svojí platební metodu a zadajte potřebné údaje. Platba bude ihned zpracována a vy budete přesměrováni zpět do naší aplikace. Na Váš mail bude odeslán mail obsahující údaje o objednávce, včetně daňového dokladu. Nyní můžete opustit prostory prodejny se svým zbožím.</p>
        </section>
        <section>
            <h2>Podpora</h2>
            <p>Pokud máte jakékoliv dotazy nebo problémy, neváhejte se obrátit na naši zákaznickou podporu. Kontaktní údaje naleznete na stránce Kontakty.</p>
        </section>
    </div>
</div>
@endsection