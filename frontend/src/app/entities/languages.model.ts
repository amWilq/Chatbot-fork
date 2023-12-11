interface ILanguage {
  languageId?: number,
  name?: string,
  icon?: string,
  categoriesId?: number[],
}

export class Language implements ILanguage {
  constructor(
    public languageId?: number,
    public name?: string,
    public icon?: string,
    public categoriesId?: number[],
  ) {}
}
