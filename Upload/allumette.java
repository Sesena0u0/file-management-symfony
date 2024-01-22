private int minmax(int profondeur) {
        if (profondeur == 0 || allumettes == 0) {
            return 0;
        }
        
        int meilleurCoup = -1;
        int meilleurScore = joueur1Tour ? Integer.MAX_VALUE : Integer.MIN_VALUE;

        for (int coup = 1; coup <= 4; coup++) {
            if (estCoupValide(coup) && coup != allumettes && coup != profondeur) {
                int score = 0;
                int nbAllumettesRestantes = allumettes - coup;

                if (nbAllumettesRestantes == 0) {
                    score = joueur1Tour ? Integer.MAX_VALUE : Integer.MIN_VALUE;
                } else {
                    joueur1Tour = !joueur1Tour; // Alternance des joueurs
                    allumettes = nbAllumettesRestantes;

                    score = minmax(profondeur - 1);

                    joueur1Tour = !joueur1Tour; // Revert la modification
                    allumettes += coup;
                }

                if ((joueur1Tour && score < meilleurScore) || (!joueur1Tour && score > meilleurScore)) {
                    meilleurCoup = coup;
                    meilleurScore = score;
                }
            }
        }

        return meilleurCoup;
    }