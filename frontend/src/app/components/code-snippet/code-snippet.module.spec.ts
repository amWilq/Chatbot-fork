import { TestBed } from '@angular/core/testing';
import { CodeSnippetComponentModule } from './code-snippet.module';

describe('CodeSnippetComponentModule', () => {
  let pipe: CodeSnippetComponentModule;

  beforeEach(() => {
    TestBed.configureTestingModule({ providers: [CodeSnippetComponentModule] });
    pipe = TestBed.inject(CodeSnippetComponentModule);
  });

  it('can load instance', () => {
    expect(pipe).toBeTruthy();
  });
});
