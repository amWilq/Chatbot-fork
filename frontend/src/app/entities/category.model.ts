interface ICategory {
  categoryId?: number,
  icon?: string,
  name?: string,
}

export class Category implements ICategory {
  constructor(
    public categoryId?: number,
    public icon?: string,
    public name?: string,
  ) {}
}
