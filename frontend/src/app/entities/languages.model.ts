interface ILanguage {
  languageId?: number,
  name?: string,
  categoriesId?: number[],
}

export class Language implements ILanguage {
  constructor(
    public languageId?: number,
    public name?: string,
    public categoriesId?: number[],
  ) {}
}
