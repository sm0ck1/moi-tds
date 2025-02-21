interface Landing {
    view: string;   // Например: "landings.dating.dat1"
    image: string | null; // URL изображения или null, если его нет
}

interface GroupedLandings {
    [group: string]: {
        [file: string]: Landing;
    };
}
