export interface Country {
    name: string;
    flag: string;
    code: string;
}

export interface CountriesDict {
    [key: string]: Country;
}
