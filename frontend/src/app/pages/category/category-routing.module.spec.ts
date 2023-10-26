import { TestBed } from '@angular/core/testing';
import { CategoryComponentRoutingModule } from './category-routing.module';

describe('CategoryComponentRoutingModule', () => {
  let pipe: CategoryComponentRoutingModule;

  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [CategoryComponentRoutingModule]
    });
    pipe = TestBed.inject(CategoryComponentRoutingModule);
  });

  it('can load instance', () => {
    expect(pipe).toBeTruthy();
  });
});
