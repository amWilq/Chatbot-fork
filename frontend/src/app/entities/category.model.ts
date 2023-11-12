interface ICategory {
  categoryId?: number,
  name?: string,
}

export class Category implements ICategory {
  constructor(
    public categoryId?: number,
    public name?: string,
  ) {}
}
