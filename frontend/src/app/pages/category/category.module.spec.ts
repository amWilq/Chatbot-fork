import { TestBed } from '@angular/core/testing';
import { CategoryModule } from './category.module';

describe('CategoryModule', () => {
  let pipe: CategoryModule;

  beforeEach(() => {
    TestBed.configureTestingModule({ providers: [CategoryModule] });
    pipe = TestBed.inject(CategoryModule);
  });

  it('can load instance', () => {
    expect(pipe).toBeTruthy();
  });
});
